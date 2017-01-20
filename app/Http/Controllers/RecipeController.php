<?php

namespace App\Http\Controllers;

use App\Recipe;
use App\RecipeCount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    public function __construct()
    {
        Cache::flush();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($parameter = null)
    {
        $recipe = Cache::tags('recipe')->remember($parameter, 60, function() use ($parameter) {
            return Recipe::where('slug', $parameter)->with(['ingredients'])->firstOrFail();
        });

        if ($recipe) {
            if (!$recipe->counts) {
                $recipe->counts()->create([]);
                $recipe->load('counts');
            }

            if ($recipe->counts) {
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

                        DB::table($table_recipe_count)
                           ->whereId($counts->id)
                           ->update($counts_arr);
                    }
                }
            }

            $ingredients = $recipe->ingredients;
            $ingredient_slug = [];

            foreach($ingredients as $ingredient) {
                $ingredient_ancestors = Cache::tags('ingredient_ancestor')->remember($ingredient->id, 60, function() use ($ingredient) {
                    return $ingredient->getAncestors();
                });

                $slug = [];

                if (!$ingredient_ancestors->isEmpty()) {
                    $slug = array_pluck($ingredient_ancestors, 'slug');
                }

                $ingredient_slug[$ingredient->id] = implode('/', array_merge($slug, [$ingredient->slug]));
            }

            return view('recipes.show', compact('recipe', 'ingredients', 'ingredient_slug'));
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($parameter = null)
    {
//        $recipe = Cache::tags('recipe')->remember($parameter, 60, function() use ($parameter) {
//            return Recipe::where('slug', $parameter)->with(['ingredients'])->firstOrFail();
//        });
        Recipe::where('slug', $parameter)->firstOrFail()->delete();
        return redirect()->route('recipes.index')->with('success', 'Recipe deleted successfully');
    }
}
