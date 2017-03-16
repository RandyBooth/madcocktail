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
use App\Scopes\ActiveScope;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Cache;
use DB;

class RecipeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isVerified', 'user-valid'], ['only' => ['create', 'edit']]);
        $this->middleware(['auth', 'isVerified', 'user-valid', 'throttle:15,5', 'xss'], ['only' => ['store', 'update']]);
        $this->middleware(['admin', 'isVerified', 'user-valid'], ['only' => ['destroy']]);
    }

    public function home()
    {
        $expiresAt = Carbon::now()->minute(60)->second(0);
        $total = 5;

        $recipes = Cache::remember('recipes_home', $expiresAt, function () use ($total) {
            return Recipe
                ::join('recipe_counts', 'recipes.id', '=', 'recipe_counts.recipe_id')
                ->join('users', 'recipes.user_id', '=', 'users.id')
                ->leftJoin('recipe_images', 'recipes.id', '=', 'recipe_images.recipe_id')
//                ->select(['title', 'slug'])
                ->where('recipe_counts.count_total', '>', $total)
                ->inRandomOrder()
                ->take(24)
                ->get();
        });

        return view('recipes.home', compact('recipes'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $now = Carbon::now()->minute(0)->second(0);
        $expiresAt = $now->copy()->minute(60);
        $total = 5;

        $recipes = Cache::remember('recipes_latest', $expiresAt, function () use ($total) {
            return Recipe
                ::join('recipe_counts', 'recipes.id', '=', 'recipe_counts.recipe_id')
                ->join('users', 'recipes.user_id', '=', 'users.id')
                ->leftJoin('recipe_images', 'recipes.id', '=', 'recipe_images.recipe_id')
//                ->select(['title', 'slug'])
                ->where('recipe_counts.count_total', '>', $total)
                ->orderby('recipes.created_at', 'DESC')
                ->orderby('recipes.title')
                ->take(24)
                ->get();
        });

//        $hour = 12;
//
//        if ($now->hour >= $hour) {
//            $hour += $hour;
//        }
//
//        $expiresAtTop = $now->copy()->hour($hour);

        $recipes_top = Cache::remember('recipes_popular', $expiresAt, function () use ($total) {
            return Recipe
                ::join('recipe_counts', 'recipes.id', '=', 'recipe_counts.recipe_id')
//                ->select(['title', 'slug'])
                ->where('recipe_counts.count_total', '>', $total)
                ->orderBy('recipe_counts.count_total', 'DESC')
                ->orderby('recipes.title')
                ->take(10)
                ->get();
        });

        return view('recipes.index', compact('recipes', 'recipes_top'));
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
            $ingredient = [];

            $count = 0;

            foreach ($ingredients_id as $token) {
                $ingredient[] = Cache::remember('ingredient_TOKEN_'.$token, 1440, function () use ($token) {
                    return Ingredient::token($token)->first();
                });
            }

            if (!empty($ingredient)) {
                $ingredients = array_pluck($ingredient, 'title', 'token');
            }
        }

        $glasses_data = Cache::remember('glasses', 1440, function () {
            return Glass::select('id', 'title', 'slug')->orderBy('title')->get();
        });

        $measures_data = Cache::remember('measures', 1440, function () {
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

            $glasses_data = Cache::remember('glasses', 1440, function () {
                return Glass::select('id', 'title', 'slug')->orderBy('title')->get();
            });

            $measures_data = Cache::remember('measures', 1440, function () {
                return Measure::select('id', 'title', 'slug')->orderBy('title')->get();
            });

            $glasses = $glasses_data->pluck('id', 'slug')->all();
            $measures = $measures_data->pluck('id', 'slug')->all();

            $data['description'] = Helper::textarea_to_array($data['description']);
            $data['directions'] = Helper::textarea_to_array($data['directions']);

            if (array_key_exists($data['glass'], $glasses)) {
                $data['glass_id'] = $glasses[$data['glass']];
            }

            $data['is_active'] = 1;
            $data['user_id'] = $user->id;

            $recipe = Recipe::create($data);

            if (!empty($recipe)) {
                $recipe->counts()->create([]);

                if (!empty($data['ingredients'])) {
                    $ingredients_data = [];
                    $ingredients = $data['ingredients'];
                    $count = 0;

                    foreach ($ingredients as $key => $token) {
                        if (!empty($token)) {
                            $ingredient = Cache::remember('ingredient_TOKEN_'.$token, 1440, function () use ($token) {
                                return Ingredient::token($token)->first();
                            });

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

                $this->clear($recipe);

                return redirect()->route('recipes.show', ['token' => $recipe->token, 'slug' => $recipe->slug])->with('success', 'Recipe "'.$recipe->title.'" has been created successfully.');
            }
        }

        return redirect()->back()->withInput()->with('danger', 'Recipe create fail.');
    }

    /**
     * Display the specified resource.
     *
     * @param $parameter
     * @return \Illuminate\Http\Response
     */
    public function show($token = null, $slug = null, Request $request)
    {
        if (!empty($token) && empty($slug)) {
            $slug = $token;
            $recipe = Cache::remember('recipe_SLUG_'.strtolower($slug), 1440, function () use ($slug) {
                return Recipe::select('token', 'slug')->where('slug', $slug)->firstOrFail();
            });

            if ($recipe) {
                return redirect()->route('recipes.show', ['token' => $recipe->token, 'slug' => $recipe->slug]);
            }
        } elseif (!empty($token) && !empty($slug)) {
            $recipe = Cache::remember('recipe_TOKENSLUG_'.$token.'_'.strtolower($slug), 1440, function () use ($token, $slug) {
                return Recipe::token($token)->where('slug', $slug)->with(['ingredients', 'glass', 'counts'])->firstOrFail();
            });

            if ($recipe) {
                $user_id = $recipe->user_id;
                $recipe_id = $recipe->id;
                $recipe_token = $recipe->token;

                $recipe_author = Cache::remember('user_ID_'.$user_id, 1440, function () use ($user_id) {
                    return User::find($user_id);
                });

                $recipe_image = Cache::remember('recipe_image_TOKEN_'.$recipe_token, 1440, function () use ($recipe_id) {
                    return RecipeImage::firstOrCreate(['recipe_id' => $recipe_id]);
                });

                $ip_id = $request->ip().'_'.$recipe_id;

                if (!Helper::is_admin()) {
                    if (!Cache::has('recipe_counter_IPID_'.$ip_id)) {
                        if (empty($recipe->counts)) {
                            $recipe->counts()->create([]);
                            $recipe->load('counts');
                            Cache::forget('recipe_TOKENSLUG_'.$token.'_'.strtolower($slug));
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
                                        Cache::put('recipe_counter_IPID_'.$ip_id, '1', (60*12)); // 12 hours
                                    }
                                }
                            }
                        }
                    }
                }

                $ingredients = $recipe->ingredients;
                $ingredient_slug = [];
                $ingredient_id = [];

                foreach ($ingredients as $ingredient) {
                    $ingredient_ancestors = Cache::remember('ingredient_ancestors_TOKEN_'.$ingredient->token, 1440, function () use ($ingredient) {
                        return $ingredient->ancestors()->select('id', 'token', 'title', 'slug')->get();
                    });

                    foreach($ingredient_ancestors as $ancestor) {
    //                    if (!$ancestor->is_alcoholic) {
                            $ingredient_id[] = $ancestor->id;
    //                    }
                    }

    //                if (!$ingredient->is_alcoholic) {
                        $ingredient_id[] = $ingredient->id;
    //                }

                    $ingredient_slug[$ingredient->id] = implode('/', array_merge(array_pluck($ingredient_ancestors, 'slug'), [$ingredient->slug]));
                }

                $recipe_similar = collect([]);

                if (!empty($ingredient_id)) {
                    $now = Carbon::now()->minute(0)->second(0);
                    $expiresAt = $now->copy()->minute(60);
                    $ingredient_id_unique = array_unique(array_flatten($ingredient_id));
                    $total = 5;

                    $recipe_similar = Cache::remember('recipe_similar_TOKEN_'.$recipe_token, $expiresAt, function () use ($recipe_id, $ingredient_id_unique, $total) {
                        return Recipe
                            ::join('recipe_counts', 'recipes.id', '=', 'recipe_counts.recipe_id')
                            ->join('ingredient_recipe', 'recipes.id', '=', 'ingredient_recipe.recipe_id')
                            ->whereIn('ingredient_recipe.ingredient_id', $ingredient_id_unique)
        //                    ->select('title', 'slug')
                            ->select(DB::raw('count(recipes.id) as recipe_count, recipes.*'))
                            ->where('recipes.id', '<>', $recipe_id)
                            ->where('recipe_counts.count_total', '>', $total)
                            ->orderby('recipe_count', 'DESC')
                            ->orderby('recipe_counts.count_total', 'DESC')
                            ->orderby('recipes.title')
                            ->take(5)
                            ->groupBy('recipes.id')
                            ->get();
                    });
                }

                return view('recipes.show', compact('recipe', 'recipe_image', 'recipe_author', 'recipe_similar', 'ingredients', 'ingredient_slug'));
            }
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
            $recipe_data = Cache::remember('recipe_TOKEN_'.$id, 1440, function () use ($id) {
                return Recipe::token($id)->with('ingredients')->first();
            });

            if ($recipe_data) {
                if (Helper::is_owner($recipe_data->user_id)) {
                    $glasses_data = Cache::remember('glasses', 1440, function () {
                        return Glass::select('id', 'title', 'slug')->orderBy('title')->get();
                    });

                    $measures_data = Cache::remember('measures', 1440, function () {
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
                        'directions' => $recipe_data->directions,
                        'ingredients' => $old_ingredients,
                        'ingredients_measure' => $old_ingredients_measure,
                        'ingredients_measure_amount' => $old_ingredients_measure_amount,
                        'glass' => $glass,
                    ];

                    if (is_array($recipe_data->description)) {
                        $recipe['description'] = implode("\n\n", $recipe_data->description);
                    }

                    if (is_array($recipe_data->directions)) {
                        $recipe['directions'] = implode("\n", $recipe_data->directions);
                    }

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

            $glasses_data = Cache::remember('glasses', 1440, function () {
                return Glass::select('id', 'title', 'slug')->orderBy('title')->get();
            });

            $measures_data = Cache::remember('measures', 1440, function () {
                return Measure::select('id', 'title', 'slug')->orderBy('title')->get();
            });

            $glasses = $glasses_data->pluck('id', 'slug')->all();
            $measures = $measures_data->pluck('id', 'slug')->all();

            $data['description'] = Helper::textarea_to_array($data['description']);
            $data['directions'] = Helper::textarea_to_array($data['directions']);
            $data['glass_id'] = '';

            if (array_key_exists($data['glass'], $glasses)) {
                $data['glass_id'] = $glasses[$data['glass']];
            }

            $recipe = Cache::remember('recipe_TOKEN_'.$id, 1440, function () use ($id) {
                return Recipe::token($id)->with('ingredients')->first();
            });

            if ($recipe) {
                if (Helper::is_owner($recipe->user_id)) {
                    $ingredients_data = [];

                    if (!empty($data['ingredients'])) {
                        $ingredients = $data['ingredients'];
                        $count = 0;

                        foreach ($ingredients as $key => $token) {
                            if (!empty($token)) {
                                $ingredient = Cache::remember('ingredient_TOKEN_'.$token, 1440, function () use ($token) {
                                    return Ingredient::token($token)->first();
                                });

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
                    }

                    if ($recipe->update($data)) {
                        $recipe->ingredients()->sync($ingredients_data);
                        $this->clear($recipe);

                        return redirect()->route('recipes.show', ['token' => $recipe->token, 'slug' => $recipe->slug])->with('success', 'Recipe "'.$recipe->title.'" has been updated successfully.');
                    }
                }
            }
        }

        return redirect()->back()->withInput()->with('danger', 'Recipe update fail.');
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
            $recipe = Recipe::withoutGlobalScope(ActiveScope::class)->token($id)->firstOrFail();

            if (Helper::is_owner($recipe->user_id)) {
                if ($recipe->delete()) {
                    $this->clear($recipe, true);

                    return redirect()->route('recipes.index')->with('success', 'Recipe "'.$recipe->title.'" has been deleted successfully.');
                }
            }
        }
    }

    public function admin_lists()
    {
        $recipes = Recipe
        ::leftJoin('recipe_images', 'recipes.id', '=', 'recipe_images.recipe_id')
        ->orderBy('recipes.updated_at', 'DESC')
        ->orderby('recipes.title')
        ->get();

        return view('recipes.admin-lists', compact('recipes'));
    }

    private function clear($recipe = null, $delete = false)
    {
        if ($recipe) {
            Cache::forget('recipe_similar_TOKEN_'.$recipe->token);
            Cache::forget('recipe_TOKEN_'.$recipe->token);
            Cache::forget('recipe_SLUG_'.$recipe->slug);
            Cache::forget('recipe_TOKENSLUG_'.$recipe->token.'_'.$recipe->slug);
            Cache::forget('user_recipes_ID_'.$recipe->user_id);

            if (Cache::has('recipes_latest')) {
                $data = Cache::get('recipes_latest');

                $data_test = $data
                    ->pluck('recipe_id')
                    ->filter(function ($value) {
                        return $value != null;
                    })
                    ->contains($recipe->id);

                if ($data_test) {
                    Cache::forget('recipes_latest');
                }
            }

            if (Cache::has('recipes_popular')) {
                $data = Cache::get('recipes_popular');

                $data_test = $data
                    ->pluck('recipe_id')
                    ->filter(function ($value) {
                        return $value != null;
                    })
                    ->contains($recipe->id);

                if ($data_test) {
                    Cache::forget('recipes_popular');
                }
            }

            if ($delete) {
                if (Cache::has('recipes_home')) {
                    $data = Cache::get('recipes_home');

                    $data_test = $data
                        ->pluck('recipe_id')
                        ->filter(function ($value) {
                            return $value != null;
                        })
                        ->contains($recipe->id);

                    if ($data_test) {
                        Cache::forget('recipes_home');
                    }
                }
            }
        }
    }
}
