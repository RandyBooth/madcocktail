<?php

namespace App\Http\Controllers;

use App\Ingredient;
use App\Recipe;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Cache;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ingredients = Ingredient::whereIsRoot()->isAlcoholic()->get();
        return view('ingredients.index')->with('ingredients', $ingredients);
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
    public function show($parameters = null)
    {
        Cache::flush();
        $parameters_explode = explode('/', $parameters);
        $count = 0;
        $parent_id = null;
        $ingredient = null;

//        $test = Ingredient::withDepth()->where('slug', '=', 'rum')->having('depth', '=', 1)->get('id');
  //        dd($test);
        foreach($parameters_explode as $parameter) {
//            $test = Ingredient::withDepth()->where('slug', '=', $parameter)->where('parent_id', '=', $parent_id)->having('depth', '=', $count++)->firstOrFail();

            $ingredient = Cache::tags('ingredient_depth')->remember($count.'_'.$parameter, 60, function() use ($parameter, $parent_id, $count) {
                return Ingredient::withDepth()->where('slug', '=', $parameter)->where('parent_id', '=', $parent_id)->having('depth', '=', $count)->firstOrFail();
            });
            $parent_id = $ingredient->id;
            $count++;
//            print_r($test); echo '<br><br>';

//            $where = function($query) use ($parameter, $count) {
//                return $query
////                    ->select()
//                    ->where('slug', '=', $parameter)
////                    ->withDepth()->where('depth', '=', $count)
//                    ;
//            };
//
//            if ($count++) {
//                $ingredients->orWhere($where);
//            } else {
//                $ingredients = Ingredient::where($where);
//            }
        }

        dd($ingredient);

        if ($count > 0 && !empty($ingredient)) {
            $ingredients = Cache::tags('ingredients')->remember($parameters, 60, function() use ($ingredients) {
                return $ingredients->get();
            });

            if ($ingredients->count() == count($parameters_explode)) {
                $ingredient = $ingredients->last();

                $ingredient_children = Cache::tags('ingredient_children')->remember($parameters, 60, function() use ($ingredient) {
                    return $ingredient->children()->get();
                });

                $ingredient_descendants_id = Cache::tags('ingredient_descendants_id')->remember($parameters, 60, function() use ($ingredient) {
                    return $ingredient->descendants()->pluck('id')->push($ingredient->id)->toArray();
                });

                $recipes = Cache::tags('ingredient_show_recipes_top')->remember($parameters, 60, function() use ($ingredient_descendants_id) {
                    return Recipe::
                    whereHas('ingredients', function($query) use($ingredient_descendants_id) {
                        $query->whereIn('ingredient_recipe.ingredient_id', $ingredient_descendants_id);
                    })
                     ->orderBy('view_count', 'desc')
                     ->orderBy('title')
                     ->take(10)
                     ->get();
                });

                return view('ingredients.show', compact('ingredient', 'ingredient_children', 'recipes', 'parameters'));
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
    public function destroy($id)
    {
        //
    }
}
