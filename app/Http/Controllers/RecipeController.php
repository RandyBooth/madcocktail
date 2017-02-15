<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecipeRequest;
use App\Glass;
use App\Helpers\Helper;
use App\Ingredient;
use App\Measure;
use App\Recipe;
use App\RecipeCount;
use App\RecipeImage;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    public function __construct()
    {
        Cache::flush();
        $this->middleware(['auth', 'isVerified'], ['only' => ['create', 'edit']]);
        $this->middleware(['xss', 'isVerified'], ['only' => ['store', 'update']]);
        $this->middleware(['admin', 'isVerified'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $total = 20;

        $recipes_latest = Cache::tags('recipe_index_latest')->remember('', 30, function () use ($total) {
            return Recipe
                ::join('recipe_counts', 'recipes.id', '=', 'recipe_counts.recipe_id')
//                ->select(['title', 'slug'])
//                ->where('recipe_counts.count_total', '>', $total)
                ->orderby('recipes.created_at', 'DESC')
                ->orderby('recipes.title')
                ->take(10)
                ->get();
        });

        $recipes_top = Cache::tags('recipe_index_popular')->remember('', (60*25), function () use ($total) {
            return Recipe
                ::join('recipe_counts', 'recipes.id', '=', 'recipe_counts.recipe_id')
//                ->select(['title', 'slug'])
//                ->where('recipe_counts.count_total', '>', $total)
                ->orderBy('recipe_counts.count_total', 'DESC')
                ->orderby('recipes.title')
                ->take(10)
                ->get();
        });

        return view('recipes.index', compact('recipes_latest', 'recipes_top'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $recipe = [
            'title' => '',
            'description' => '',
            'directions' => '',
            'ingredients' => '',
            'ingredients_measure' => '',
            'ingredients_measure_amount' => '',
            'glass' => '',
        ];
        $ingredients = [];

        if (!empty(old('ingredients'))) {
            $ingredients_id = old('ingredients');
            $ingredients_query = [];

            $count = 0;

            foreach ($ingredients_id as $token) {
                $where = function ($query) use ($token, $count) {
                    return $query->token($token);
                };

                if ($count++) {
                    $ingredients_query->orWhere($where);
                } else {
                    $ingredients_query = Ingredient::select('title', 'token')->where($where);
                }
            }

            $ingredients_data = $ingredients_query->get();

            if (!$ingredients_data->isEmpty()) {
                $ingredients = $ingredients_data->pluck('title', 'token')->all();
            }
        }

        $glasses_data = Cache::tags('recipe_glasses')->remember('', 60, function () {
            return Glass::select('id', 'title', 'slug')->orderBy('title')->get();
        });

        $measures_data = Cache::tags('recipe_measures')->remember('', 60, function () {
            return Measure::select('id', 'title', 'slug')->orderBy('title')->get();
        });

        $glasses = $glasses_data->pluck('title', 'slug')->all();
        $measures = $measures_data->pluck('title', 'slug')->all();

        return view('recipes.create', compact('recipe', 'ingredients', 'glasses', 'measures'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RecipeRequest $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $data = $request->all();

            $glasses_data = Cache::tags('recipe_glasses')->remember('', 60, function () {
                return Glass::select('id', 'title', 'slug')->orderBy('title')->get();
            });

            $measures_data = Cache::tags('recipe_measures')->remember('', 60, function () {
                return Measure::select('id', 'title', 'slug')->orderBy('title')->get();
            });

            $glasses = $glasses_data->pluck('id', 'slug')->all();
            $measures = $measures_data->pluck('id', 'slug')->all();

            $data['directions'] = Helper::textarea_to_array($data['directions']);

            if (array_key_exists($data['glass'], $glasses)) {
                $data['glass_id'] = $glasses[$data['glass']];
            }

            $data['is_active'] = ($user->role == 1) ? 1 : 0;
            $data['user_id'] = $user->id;

            $recipe = Recipe::create($data);

            if (!empty($recipe)) {
                if (!empty($data['ingredients'])) {
                    $ingredients_data = [];
                    $ingredients = $data['ingredients'];
                    $count = 0;

                    foreach ($ingredients as $key => $token) {
                        if (!empty($token)) {
                            $ingredient = Ingredient::select('id')->token($token)->first();

                            if ($ingredient) {
                                $ingredients_data[$ingredient->id] = ['order_by' => $count++];

                                if (!empty($data['ingredients_measure'][$key])) {
                                    if (array_key_exists($data['ingredients_measure'][$key], $measures)) {
                                        $ingredients_data[$ingredient->id]['measure_id'] = $measures[$data['ingredients_measure'][$key]];
                                    }
                                }

                                if (!empty($data['ingredients_measure_amount'][$key])) {
                                    $ingredients_data[$ingredient->id]['measure_amount'] = Helper::fraction_to_decimal($data['ingredients_measure_amount'][$key]);
                                }
                            }
                        }
                    }

                    $recipe->ingredients()->sync($ingredients_data);
                }

                return redirect()->route('recipes.show', $recipe->slug)->with('success', 'Recipe ('.$recipe->title.') has been created successfully.');
            }
        }

        return redirect()->back()->withInput()->with('warning', 'Recipe create fail.');
    }

    /**
     * Display the specified resource.
     *
     * @param $parameter
     * @return \Illuminate\Http\Response
     */
    public function show($parameter = null, Request $request)
    {
        $recipe = Cache::tags('recipe')->remember(strtolower($parameter), 60, function () use ($parameter) {
            return Recipe::where('slug', $parameter)->with(['ingredients'])->firstOrFail();
        });

        if ($recipe) {
            $recipe_image = Cache::tags('recipe_image')->remember($recipe->id, 60, function () use ($recipe) {
                return RecipeImage::image($recipe->id)->first();
            });

            $ip_id = $request->ip().'_'.$recipe->id;

            if (!Cache::tags('recipe_counter')->has($ip_id) && !Helper::is_admin()) {
                if (empty($recipe->counts)) {
                    $recipe->counts()->create([]);
                    $recipe->load('counts');
                }

                if (!empty($recipe->counts)) {
                    $counts = $recipe->counts;
                    $table_recipe_count = with(new RecipeCount)->getTable();

                    if (!empty($table_recipe_count)) {
                        $counts_arr = [];
                        $count_updated = $counts->updated_at;
                        $count_now = Carbon::now();

                        if (isset($counts->count_total)) {
                            $counts_arr['count_total'] = DB::raw('count_total + 1');
                        }

                        if (isset($counts->count_year)) {
                            $count_updated_start_year = $count_updated->copy()->startOfYear();
                            $count_now_start_year = $count_now->copy()->startOfYear();

                            if ($count_updated_start_year->diffInYears($count_now_start_year, false) > 0) {
                                $counts_arr['count_year'] = 1;
                            } else {
                                $counts_arr['count_year'] = DB::raw('count_year + 1');
                            }
                        }

                        if (isset($counts->count_month)) {
                            $count_updated_start_month = $count_updated->copy()->startOfMonth();
                            $count_now_start_month = $count_now->copy()->startOfMonth();

                            if ($count_updated_start_month->diffInMonths($count_now_start_month, false) > 0) {
                                $counts_arr['count_month'] = 1;
                            } else {
                                $counts_arr['count_month'] = DB::raw('count_month + 1');
                            }
                        }

                        if (isset($counts->count_day)) {
                            $count_updated_start_day = $count_updated->copy()->startOfDay();
                            $count_now_start_day = $count_now->copy()->startOfDay();

                            if ($count_updated_start_day->diffInDays($count_now_start_day, false) > 0) {
                                $counts_arr['count_day'] = 1;
                            } else {
                                $counts_arr['count_day'] = DB::raw('count_day + 1');
                            }
                        }

                        if (!empty($counts_arr)) {
                            $counts_arr['updated_at'] = $count_now;

                            $insert_count = DB::table($table_recipe_count)
                               ->whereId($counts->id)
                               ->update($counts_arr);

                            if ($insert_count) {
                                Cache::tags('recipe_counter')->put($ip_id, '1', (60*12)); // 12 hours
                            }
                        }
                    }
                }
            }

            $ingredients = $recipe->ingredients;
            $ingredient_slug = [];

            foreach ($ingredients as $ingredient) {
                $ingredient_ancestors = Cache::tags('ingredient_ancestor')->remember($ingredient->id, 60, function () use ($ingredient) {
                    return $ingredient->getAncestors();
                });

                $slug = [];

                if (!$ingredient_ancestors->isEmpty()) {
                    $slug = array_pluck($ingredient_ancestors, 'slug');
                }

                $ingredient_slug[$ingredient->id] = implode('/', array_merge($slug, [$ingredient->slug]));
            }

            return view('recipes.show', compact('recipe', 'recipe_image', 'ingredients', 'ingredient_slug'));
        }
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
            $recipe_data = Recipe::token($id)->with('ingredients')->firstOrFail();

            if ($recipe_data) {
                if (Helper::is_owner($recipe_data->user_id)) {
                    $glasses_data = Cache::tags('recipe_glasses')->remember('', 60, function () {
                        return Glass::select('id', 'title', 'slug')->orderBy('title')->get();
                    });

                    $measures_data = Cache::tags('recipe_measures')->remember('', 60, function () {
                        return Measure::select('id', 'title', 'slug')->orderBy('title')->get();
                    });

                    $glasses = $glasses_data->pluck('slug', 'id')->all();
                    $measures = $measures_data->pluck('slug', 'id')->all();

                    $ingredients_data = $recipe_data->ingredients;
                    $ingredients = [];
                    $old_ingredients = [];
                    $old_ingredients_measure = [];
                    $old_ingredients_measure_amount = [];

                    if (!$ingredients_data->isEmpty()) {
                        foreach($ingredients_data as $val) {
                            $old_ingredients[] = $val->token;
                            $ingredients[$val->token] = $val->title;

                            $old_ingredients_measure[] = (isset($measures[$val->pivot->measure_id]))
                                ? $measures[$val->pivot->measure_id]
                                : '';

                            $measure_amount = Helper::trim_trailing_zeroes($val->pivot->measure_amount);

                            $old_ingredients_measure_amount[] = (!empty($measure_amount))
                                ? $measure_amount
                                : '';
                        }
                    }

                    $glass = '';

                    if ($recipe_data->glass_id) {
                        if (isset($glasses[$recipe_data->glass_id])) {
                            $glass = $glasses[$recipe_data->glass_id];
                        }
                    }

                    $recipe = [
                        'token' => $recipe_data->token,
                        'title' => $recipe_data->title,
                        'description' => $recipe_data->description,
                        'directions' => implode("\n", $recipe_data->directions),
                        'ingredients' => $old_ingredients,
                        'ingredients_measure' => $old_ingredients_measure,
                        'ingredients_measure_amount' => $old_ingredients_measure_amount,
                        'glass' => $glass,
                    ];

                    $glasses = $glasses_data->pluck('title', 'slug')->all();
                    $measures = $measures_data->pluck('title', 'slug')->all();

                    return view('recipes.edit', compact('recipe', 'ingredients', 'glasses', 'measures'));
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
    public function update(RecipeRequest $request, $id)
    {
        if (Auth::check()) {
            $data = $request->all();

            $glasses_data = Cache::tags('recipe_glasses')->remember('', 60, function () {
                return Glass::select('id', 'title', 'slug')->orderBy('title')->get();
            });

            $measures_data = Cache::tags('recipe_measures')->remember('', 60, function () {
                return Measure::select('id', 'title', 'slug')->orderBy('title')->get();
            });

            $glasses = $glasses_data->pluck('id', 'slug')->all();
            $measures = $measures_data->pluck('id', 'slug')->all();

            $data['directions'] = Helper::textarea_to_array($data['directions']);

            if (array_key_exists($data['glass'], $glasses)) {
                $data['glass_id'] = $glasses[$data['glass']];
            }

            $recipe = Recipe::token($id)->firstOrFail();

            if (Helper::is_owner($recipe->user_id)) {
                if (!empty($data['ingredients'])) {
                    $ingredients_data = [];
                    $ingredients = $data['ingredients'];
                    $count = 0;

                    foreach ($ingredients as $key => $token) {
                        if (!empty($token)) {
                            $ingredient = Ingredient::select('id')->token($token)->first();

                            if ($ingredient) {
                                $ingredients_data[$ingredient->id] = ['order_by' => $count++];

                                if (!empty($data['ingredients_measure'][$key])) {
                                    if (array_key_exists($data['ingredients_measure'][$key], $measures)) {
                                        $ingredients_data[$ingredient->id]['measure_id'] = $measures[$data['ingredients_measure'][$key]];
                                    }
                                }

                                if (!empty($data['ingredients_measure_amount'][$key])) {
                                    $ingredients_data[$ingredient->id]['measure_amount'] = Helper::fraction_to_decimal($data['ingredients_measure_amount'][$key]);
                                }
                            }
                        }
                    }

                    $recipe->ingredients()->sync($ingredients_data);
                }

                return redirect()->route('recipes.show', $recipe->slug)->with('success', 'Recipe ('.$recipe->title.') has been updated successfully.');
            }
        }

        return redirect()->back()->withInput()->with('warning', 'Recipe update fail.');
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
            $recipe = Recipe::token($id)->firstOrFail();
            $recipe->delete();
            return redirect()->route('recipes.index')->with('success', 'Recipe ('.$recipe->title.') has been deleted successfully.');
        }
    }
}
