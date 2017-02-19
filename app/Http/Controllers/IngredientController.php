<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\IngredientRequest;
use App\Ingredient;
use App\Recipe;
use Auth;
use Illuminate\Support\Facades\Cache;

class IngredientController extends Controller
{

    public function __construct()
    {
        Cache::flush();
        $this->middleware(['admin', 'isVerified'], ['only' => ['create', 'edit', 'destroy']]);
        $this->middleware(['xss', 'isVerified'], ['only' => ['store', 'update']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ingredients = Cache::tags('index.ingredient')->remember('', 60, function () {
            return Ingredient::whereIsRoot()->isAlcoholic()->defaultOrder()->get();
        });

        return view('ingredients.index', compact('ingredients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ingredient = [
            'title' => '',
            'ingredients' => ''
        ];
        $ingredients = ['' => ''];

        $nodes = Cache::tags('ingredient_create_tree')->remember('', 60, function () {
            return Ingredient::orderBy('is_alcoholic', 'desc')->orderBy('title')->get()->toTree();
        });

        $traverse = function ($ingredients_arr, $prefix = '-') use (&$traverse, &$ingredients) {
            foreach ($ingredients_arr as $val) {
                $ingredients[$val->token] = $prefix.' '.$val->title;
                $traverse($val->children, $prefix.'-');
            }
        };

        $traverse($nodes);
        return view('ingredients.create', compact('ingredient', 'ingredients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\IngredientRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IngredientRequest $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $data = $request->all();

            $data['parent_id'] = null;
            $data['is_alcoholic'] = 0;
            $data['is_active'] = ($user->role == 1) ? 1 : 0;
            $data['user_id'] = Auth::id();

            if (!empty($data['ingredients'])) {
                $ingredient_id = Ingredient::token($data['ingredients'])->pluck('id')->first();

                if ($ingredient_id) {
                    $data['parent_id'] = $ingredient_id;
                }
            }

            $ingredient = Ingredient::create($data);

            if (!empty($ingredient)) {
                $ingredient_slug = $ingredient->slug;

                if ($data['parent_id']) {
                    $ingredient_ancestors = $ingredient->ancestors()->get();
                    $ingredient_ancestors_self = array_merge($ingredient_ancestors->toArray(), [$ingredient->toArray()]);
                    $ingredient_ancestors_self_slug = array_pluck($ingredient_ancestors_self, 'slug');
                    $ingredient_slug = implode('/', $ingredient_ancestors_self_slug);
                }

                return redirect()->route('ingredients.show', $ingredient_slug)->with('success', 'Ingredient ('.$ingredient->title.') has been created successfully.');
            }
        }

        return redirect()->back()->withInput()->with('danger', 'Ingredient create fail.');
    }

    /**
     * Display the specified resource.
     *
     * @param $parameters
     * @return \Illuminate\Http\Response
     */
    public function show($parameters = null)
    {
        $parameters_explode = explode('/', $parameters);
        $count_parameters = count($parameters_explode);

        $ingredient = Cache::tags('ingredient')->remember(strtolower($parameters), 60, function () use ($parameters_explode) {
            return Ingredient::where('slug', last($parameters_explode))->firstOrFail();
        });

        $ingredient_ancestors = Cache::tags('ingredients_ancestors')->remember(strtolower($parameters), 60, function () use ($ingredient) {
            return $ingredient->ancestors()->get();
        });

        $ingredient_ancestors_self = array_merge($ingredient_ancestors->toArray(), [$ingredient->toArray()]);
        $ingredient_ancestors_self_slug = array_pluck($ingredient_ancestors_self, 'slug');
        $count = 0;
        $count_ingredient_valid = 0;

        for ($count; $count < $count_parameters; $count++) {
            if (isset($ingredient_ancestors_self_slug[$count])) {
                if ($parameters_explode[$count] == $ingredient_ancestors_self_slug[$count]) {
                    $count_ingredient_valid++;
                }
            }
        }

        if ($count_parameters == $count_ingredient_valid) {
            $ingredient_breadcrumbs = array_pluck($ingredient_ancestors_self, 'title', 'slug');

            $ingredients = Cache::tags('ingredient_children')->remember(strtolower($parameters), 60, function () use ($ingredient) {
                return $ingredient->
                    children()->
                    orderBy('title')->
                    get();
            });

            $ingredient_descendants_id = Cache::tags('ingredient_descendants_id')->remember(strtolower($parameters), 60, function () use ($ingredient) {
                return $ingredient->
                    descendants()->
                    pluck('id')->
                    push($ingredient->id)->
                    toArray();
            });

            $recipes = Cache::tags('ingredient_show_recipes_top_month')->remember(strtolower($parameters), 60, function () use ($ingredient_descendants_id) {
                return Recipe::
                    whereHas('ingredients', function($query) use($ingredient_descendants_id) {
                        $query->whereIn('ingredient_recipe.ingredient_id', $ingredient_descendants_id);
                    })
                    ->join('recipe_counts', 'recipes.id', '=', 'recipe_counts.recipe_id')
                    ->select('title', 'slug')
//                    ->where('recipe_counts.count_month', '>=', 5)
                    ->orderBy('recipe_counts.count_month', 'DESC')
                    ->orderby('recipes.title')
                    ->take(10)
                    ->get();
            });

            /*$recipes = Cache::tags('ingredient_show_recipes_top_day')->remember(strtolower($parameters), 60, function() use ($ingredient_descendants_id) {
                return Recipe::
                    whereHas('ingredients', function($query) use($ingredient_descendants_id) {
                        $query->
                            whereIn('ingredient_recipe.ingredient_id', $ingredient_descendants_id);
                    })
                    ->with('counts')
                    ->join('recipe_counts', 'recipes.id', '=', 'recipe_counts.recipe_id')
                    ->orderBy('recipe_counts.count_day', 'DESC')
                    ->orderby('title')
                    ->take(10)
                    ->get();
            });*/

            return view('ingredients.show', compact('ingredient', 'ingredients', 'ingredient_breadcrumbs', 'recipes', 'parameters'));
        }

        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::check()) {
            $ingredient_data = Ingredient::token($id)->firstOrFail();

            if ($ingredient_data) {
                if (Helper::is_owner($ingredient_data->user_id)) {
                    $ingredients_token = null;

                    if ($ingredient_data->parent_id) {
                        $parent = Ingredient::select('token')->find($ingredient_data->parent_id);

                        if ($parent) {
                            $ingredients_token = $parent->token;
                        }
                    }

                    $ingredient = [
                        'token' => $ingredient_data->token,
                        'title' => $ingredient_data->title,
                        'ingredients' => $ingredients_token
                    ];
                    $ingredients = ['' => ''];

                    $nodes = Cache::tags('ingredient_create_tree')->remember('', 60, function () {
                        return Ingredient::orderBy('is_alcoholic', 'desc')->orderBy('title')->get()->toTree();
                    });

                    $traverse = function ($ingredients_arr, $prefix = '-') use (&$traverse, &$ingredients) {
                        foreach ($ingredients_arr as $val) {
                            $ingredients[$val->token] = $prefix.' '.$val->title;
                            $traverse($val->children, $prefix.'-');
                        }
                    };

                    $traverse($nodes);
                    return view('ingredients.edit', compact('ingredient', 'ingredients'));
                }
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(IngredientRequest $request, $id)
    {
        if (Auth::check()) {
            $data = $request->all();
            $data['parent_id'] = null;

            if (!empty($data['ingredients'])) {
                $ingredient_id = Ingredient::token($data['ingredients'])->pluck('id')->first();

                if ($ingredient_id) {
                    $data['parent_id'] = $ingredient_id;
                }
            }

            $ingredient = Ingredient::token($id)->firstOrFail();

            if (Helper::is_owner($ingredient->user_id)) {
                $ingredient->title = $data['title'];
                $ingredient_save = $ingredient->parent()->associate($data['parent_id'])->save();

                if ($ingredient_save) {
                    $ingredient_slug = $ingredient->slug;

                    if ($data['parent_id']) {
                        $ingredient_ancestors = $ingredient->ancestors()->get();
                        $ingredient_ancestors_self = array_merge($ingredient_ancestors->toArray(), [$ingredient->toArray()]);
                        $ingredient_ancestors_self_slug = array_pluck($ingredient_ancestors_self, 'slug');
                        $ingredient_slug = implode('/', $ingredient_ancestors_self_slug);
                    }

                    return redirect()->route('ingredients.show', $ingredient_slug)->with('success', 'Ingredient ('.$ingredient->title.') has been updated successfully.');
                }
            }
        }

        return redirect()->back()->withInput()->with('danger', 'Ingredient update fail.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Helper::is_admin()) {
            $ingredient = Ingredient::token($id)->firstOrFail();
            $ingredient->delete();
            return redirect()->route('ingredients.index')->with('success', 'Ingredient ('.$ingredient->title.') has been deleted successfully.');
        }
    }

    public function tree()
    {
        $nodes = Ingredient::orderBy('is_alcoholic', 'desc')->orderBy('title')->get()->toTree();
        $tree = '';
        $this->buildTree($tree, $nodes, 'title', 'children', true);
        return view('ingredients.tree', compact('tree'));
    }

    private function buildTree(&$tree_return = '', $tree_array, $display_field, $children_field, $link = false, $slug = '', $recursionDepth = 0, $maxDepth = false)
    {
        if ($maxDepth && ($recursionDepth == $maxDepth)) return;

        if (!empty($tree_array)) {
            $tree_return .= "<ul>\n";

            foreach ($tree_array as $row) {
                $tree_return .= "<li>\n";
                $tree_return .= ($link) ? "<a href=\"".route('ingredients.show', $slug.$row['slug'].'/')."\">" . $row[$display_field] . "</a>\n" : $row[$display_field] . "\n";

                if (isset($row[$children_field])) {
                    if (!empty($row[$children_field])) {
                        $this->buildTree($tree_return, $row[$children_field], $display_field, $children_field, $link, $slug.$row['slug'].'/', $recursionDepth + 1, $maxDepth);
                    }
                }

                $tree_return .= "</li>\n";
            }

            $tree_return .= "</ul>\n";
        }
    }
}
