<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\IngredientRequest;
use App\Ingredient;
use App\Recipe;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class IngredientController extends Controller
{
    public function __construct()
    {
//        Cache::flush();
        $this->middleware(['auth', 'isVerified', 'user-valid'], ['only' => ['create']]);
        $this->middleware(['auth', 'isVerified', 'user-valid', 'throttle:20,5', 'xss'], ['only' => ['store']]);
        $this->middleware(['admin', 'isVerified', 'user-valid'], ['only' => ['edit', 'destroy']]);
        $this->middleware(['admin', 'isVerified', 'user-valid', 'xss'], ['only' => ['update']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ingredients = Cache::remember('ingredients_root', 10080, function () {
            return Ingredient::whereIsRoot()->isActive()->isAlcoholic()->defaultOrder()->get();
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
        $ingredients = ['' => '&nbsp;'];

        $nodes = Cache::remember('ingredients_tree', 10080, function () {
            return Ingredient::orderBy('is_alcoholic', 'desc')->orderBy('title')->get()->toTree();
        });

        $traverse = function ($ingredients_arr, $prefix = '-') use (&$traverse, &$ingredients) {
            foreach ($ingredients_arr as $val) {
                $pending = (!$val->is_active) ? ' (pending)': '';
                $ingredients[$val->token] = $prefix.' '.$val->title.$pending;
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
                $token = $data['ingredients'];

                $ingredient = Cache::remember('ingredient_TOKEN_'.$token, 10080, function () use ($token) {
                    return Ingredient::token($token)->first();
                });

                if ($ingredient) {
                    $data['parent_id'] = $ingredient->id;
                }
            }

            $ingredient = Ingredient::create($data);

            if (!empty($ingredient)) {
                $this->clear($ingredient);

                if ($data['is_active']) {
                    $ingredient_slug = $ingredient->slug;

                    if ($data['parent_id']) {
                        $ingredient_ancestors = Cache::remember('ingredient_ancestors_TOKEN_'.$ingredient->token, 10080, function () use ($ingredient) {
                            return $ingredient->ancestors()->select('id', 'token', 'title', 'slug')->get();
                        });

                        $ingredient_ancestors_self = array_merge($ingredient_ancestors->toArray(), [$ingredient->toArray()]);
                        $ingredient_ancestors_self_slug = array_pluck($ingredient_ancestors_self, 'slug');
                        $ingredient_slug = implode('/', $ingredient_ancestors_self_slug);
                    }

                    return redirect()->route('ingredients.show', $ingredient_slug)->with('success', 'Ingredient "'.$ingredient->title.'" has been created successfully.');
                }

                return redirect()->route('ingredients.create')->with('success', 'Ingredient "'.$ingredient->title.'" has been created successfully. Waiting on approval from admin.');
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
        $count_parameters = 0;
        $count_ingredient_valid = 0;

        if (!empty($parameters)) {
            $parameters_explode = explode('/', $parameters);
            $count_parameters = count($parameters_explode);
            $last_parameter = last($parameters_explode);

            $ingredient = Cache::remember('ingredient_SLUG_'.strtolower($last_parameter), 10080, function () use ($last_parameter) {
                return Ingredient::where('slug', $last_parameter)->isActive()->firstOrFail();
            });

            $ingredient_ancestors = Cache::remember('ingredient_ancestors_TOKEN_'.$ingredient->token, 10080, function () use ($ingredient) {
                return $ingredient->ancestors()->select('id', 'token', 'title', 'slug')->get();
            });

            $ingredient_ancestors_self = array_merge($ingredient_ancestors->toArray(), [$ingredient->toArray()]);
            $ingredient_ancestors_self_slug = array_pluck($ingredient_ancestors_self, 'slug');
            $count = 0;

            for ($count; $count < $count_parameters; $count++) {
                if (isset($ingredient_ancestors_self_slug[$count])) {
                    if ($parameters_explode[$count] == $ingredient_ancestors_self_slug[$count]) {
                        $count_ingredient_valid++;
                    }
                }
            }
        } else {
            $ingredients = Cache::remember('ingredients_root', 10080, function () {
                return Ingredient::whereIsRoot()->isActive()->isAlcoholic()->defaultOrder()->get();
            });
        }

        if ($count_parameters == $count_ingredient_valid) {
            $ingredient_descendants_id = [];

            if ($count_ingredient_valid > 0) {
                $recipe_month_text = 'ingredient_recipes_top_month_TOKEN_'.$ingredient->token;
                $ingredient_breadcrumbs = array_pluck($ingredient_ancestors_self, 'title', 'slug');

                $ingredients = Cache::remember('ingredient_children_TOKEN_'.$ingredient->token, 10080, function () use ($ingredient) {
                    return $ingredient->children()->orderBy('title')->get();
                });

                $ingredient_descendants = Cache::remember('ingredient_descendants_TOKEN_'.$ingredient->token, 10080, function () use ($ingredient) {
                    return $ingredient->descendants()->select('id', 'token', 'title', 'slug')->get();
                });

                if (!$ingredient_descendants->isEmpty()) {
                    $ingredient_descendants_id[] = $ingredient_descendants->pluck('id')->push($ingredient->id)->toArray();
                }
            } else {
                $recipe_month_text = 'ingredient_recipes_top_month';

                foreach($ingredients as $val) {
                    $ingredient_descendants = Cache::remember('ingredient_descendants_TOKEN_'.$val->token, 10080, function () use ($val) {
                        return $val->descendants()->select('id', 'token', 'title', 'slug')->get();
                    });

                    if (!$ingredient_descendants->isEmpty()) {
                        $ingredient_descendants_id[] = $ingredient_descendants->pluck('id')->push($val->id)->toArray();
                    }
                }
            }

            $now = Carbon::now()->minute(0)->second(0);
            $expiresAt = $now->copy()->minute(60);
            $total = 5;

            $recipes = Cache::remember($recipe_month_text, $expiresAt, function () use ($ingredient_descendants_id, $total) {
                return Recipe
                    ::join('recipe_counts', 'recipes.id', '=', 'recipe_counts.recipe_id')
                    ->select('title', 'slug')
                    ->whereHas('ingredients', function ($query) use ($ingredient_descendants_id) {
                        $query->whereIn('ingredient_recipe.ingredient_id', array_unique(array_flatten($ingredient_descendants_id)));
                    })
                    ->where('recipe_counts.count_total', '>', $total)
                    ->orderBy('recipe_counts.count_total', 'DESC')
                    ->orderby('recipes.title')
                    ->take(10)
                    ->get();
            });

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
            $ingredient_data = Cache::remember('ingredient_TOKEN_'.$id, 10080, function () use ($id) {
                return Ingredient::token($id)->first();
            });

            if ($ingredient_data) {
                if (Helper::is_owner($ingredient_data->user_id)) {
                    $ingredients_token = null;

                    if ($ingredient_data->parent_id) {
                        $ingredient_parent_id = $ingredient_data->parent_id;

                        $parent = Cache::remember('ingredient_parent_TOKEN_'.$ingredient_data->token, 10080, function () use ($ingredient_parent_id) {
                            return Ingredient::select('token')->find($ingredient_parent_id);
                        });

                        if ($parent) {
                            $ingredients_token = $parent->token;
                        }
                    }

                    $ingredient = [
                        'token' => $ingredient_data->token,
                        'title' => $ingredient_data->title,
                        'ingredients' => $ingredients_token
                    ];
                    $ingredients = ['' => '&nbsp;'];

                    $nodes = Cache::remember('ingredients_tree', 10080, function () {
                        return Ingredient::orderBy('is_alcoholic', 'desc')->orderBy('title')->get()->toTree();
                    });

                    $traverse = function ($ingredients_arr, $prefix = '-') use (&$traverse, &$ingredients) {
                        foreach ($ingredients_arr as $val) {
                            $pending = (!$val->is_active) ? ' (pending)': '';
                            $ingredients[$val->token] = $prefix.' '.$val->title.$pending;
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
            $user = Auth::user();
            $data = $request->all();

            $data['is_active'] = ($user->role == 1) ? 1 : 0;
            $data['parent_id'] = null;

            if (!empty($data['ingredients'])) {
                $token = $data['ingredients'];

                $ingredient = Cache::remember('ingredient_TOKEN_'.$token, 10080, function () use ($token) {
                    return Ingredient::token($token)->first();
                });

                if ($ingredient) {
                    $data['parent_id'] = $ingredient->id;
                }
            }

            $ingredient = Cache::remember('ingredient_TOKEN_'.$id, 10080, function () use ($id) {
                return Ingredient::token($id)->first();
            });

            if ($ingredient) {
                if (Helper::is_owner($ingredient->user_id)) {
                    $ingredient->title = $data['title'];
                    $ingredient->is_active = $data['is_active'];

                    if ($ingredient->parent()->associate($data['parent_id'])->save()) {
                        $this->clear($ingredient);

                        $ingredient_slug = $ingredient->slug;

                        if ($data['parent_id']) {
                            $ingredient_ancestors = Cache::remember('ingredient_ancestors_TOKEN_'.$ingredient->token, 10080, function () use ($ingredient) {
                                return $ingredient->ancestors()->select('id', 'token', 'title', 'slug')->get();
                            });

                            $ingredient_ancestors_self = array_merge($ingredient_ancestors->toArray(), [$ingredient->toArray()]);
                            $ingredient_ancestors_self_slug = array_pluck($ingredient_ancestors_self, 'slug');
                            $ingredient_slug = implode('/', $ingredient_ancestors_self_slug);
                        }

                        return redirect()->route('ingredients.show', $ingredient_slug)->with('success', 'Ingredient "'.$ingredient->title.'" has been updated successfully.');
                    }
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

            if ($ingredient->delete()) {
                $this->clear($ingredient);

                return redirect()->route('ingredients.index')->with('success', 'Ingredient "'.$ingredient->title.'" has been deleted successfully.');
            }
        }
    }

    public function tree()
    {
        $nodes = Cache::remember('ingredients_tree', 10080, function () {
            return Ingredient::orderBy('is_alcoholic', 'desc')->orderBy('title')->get()->toTree();
        });

        $tree = '';
        $this->buildTree($tree, $nodes, 'title', 'children', true);
        return view('ingredients.tree', compact('tree'));
    }

    public function pending()
    {
        $ingredients = Ingredient::isActive(0)->orderBy('is_alcoholic', 'desc')->orderBy('created_at')->orderBy('title')->get();
        $ingredients_slug = [];

        if (!$ingredients->isEmpty()) {
            foreach($ingredients as $ingredient) {
                $ingredient_ancestors = $ingredient->ancestors()->select('id', 'token', 'title', 'slug')->get();

                $ingredient_ancestors_self = array_merge($ingredient_ancestors->toArray(), [$ingredient->toArray()]);
                $ingredient_ancestors_self_slug = array_pluck($ingredient_ancestors_self, 'slug');
                $ingredients_slug[$ingredient->id] = implode('/', $ingredient_ancestors_self_slug);
            }
        }

        return view('ingredients.pending', compact('ingredients', 'ingredients_slug'));
    }

    private function buildTree(&$tree_return = '', $tree_array, $display_field, $children_field, $link = false, $slug = '', $recursionDepth = 0, $maxDepth = false)
    {
        if ($maxDepth && ($recursionDepth == $maxDepth)) return;

        if (!empty($tree_array)) {
            $tree_return .= "<ul>\n";

            foreach ($tree_array as $row) {
                $pending = (!$row['is_active']) ? ' (pending)': '';
                $tree_return .= "<li>\n";

                $tree_return .= ($link) ? "<a href=\"".route('ingredients.show', $slug.$row['slug'].'/')."\">" . $row[$display_field] . $pending . "</a>\n" : $row[$display_field] . $pending . "\n";

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

    private function clear($ingredient = null)
    {
        if ($ingredient) {
            $ingredient_ancestors = Cache::remember('ingredient_ancestors_TOKEN_'.$ingredient->token, 10080, function () use ($ingredient) {
                return $ingredient->ancestors()->select('id', 'token', 'title', 'slug')->get();
            });

            if (!$ingredient_ancestors->isEmpty()) {
                foreach($ingredient_ancestors as $val) {
                    $this->clearGroup($val);
                }
            }

            $ingredient_descendants = Cache::remember('ingredient_descendants_TOKEN_'.$ingredient->token, 10080, function () use ($ingredient) {
                return $ingredient->descendants()->select('id', 'token', 'title', 'slug')->get();
            });

            if (!$ingredient_descendants->isEmpty()) {
                foreach($ingredient_ancestors as $val) {
                    $this->clearGroup($val);
                }
            }

            $this->clearGroup($ingredient);
        }

        Cache::forget('ingredients_root');
        Cache::forget('ingredients_tree');
        Cache::forget('ingredient_recipes_top_month');
    }

    private function clearGroup($ingredient)
    {
        Cache::forget('ingredient_SLUG_'.$ingredient->slug);
        Cache::forget('ingredient_TOKEN_'.$ingredient->token);
        Cache::forget('ingredient_parent_TOKEN_'.$ingredient->token);
        Cache::forget('ingredient_ancestors_TOKEN_'.$ingredient->token);
        Cache::forget('ingredient_children_TOKEN_'.$ingredient->token);
        Cache::forget('ingredient_descendants_TOKEN_'.$ingredient->token);
        Cache::forget('ingredient_recipes_top_month_TOKEN_'.$ingredient->token);
    }
}
