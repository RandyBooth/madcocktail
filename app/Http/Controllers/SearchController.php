<?php

namespace App\Http\Controllers;

use App\Ingredient;
use App\Recipe;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct()
    {
        Cache::flush();
    }

    public function search(Request $request, $type = null)
    {
        if ($request->has('search-id') && $request->has('search-group')) {
            $id = $request->input('search-id');
            $group = strtolower($request->input('search-group'));

            if ($group == 'recipes') {
                $recipe = Recipe::token($id)->first();

                if ($recipe) {
                    return redirect()->route('recipes.show', $recipe->slug);
                }
            } elseif ($group == 'ingredients') {
                $ingredient = Ingredient::token($id)->first();

                if ($ingredient) {
                    $ingredient_ancestors = $ingredient->ancestors()->get();
                    $ingredient_ancestors_self = array_merge($ingredient_ancestors->toArray(), [$ingredient->toArray()]);
                    $ingredient_ancestors_self_slug = array_pluck($ingredient_ancestors_self, 'slug');

                    return redirect()->route('ingredients.show', implode('/', $ingredient_ancestors_self_slug));
                }
            }
        }

//        if ($request->has('search')) {
//            $query = preg_replace('/\s+/', ' ', trim($request->input('search')));
//
//            if (strlen($query) >= 3) {
//                $type_arr = $this->_searchType($type);
//                $data = $this->_search($query, $type_arr, 'search', 20);
//
//                foreach ($type_arr as $data_single) {
//                    if (isset($data[$data_single])) {
//                        $arr = $data[$data_single];
//                        $arr_title = array_pluck($arr, 'title', 'token');
//                        $arr_unique = array_unique($arr_title);
//
//                        foreach($arr_unique as $key => $val) {
//                            $suggestions[] = ['value' => $val, 'id' => $key, 'data' => ['group' => title_case($data_single)]];
//                        }
//                    }
//                }
//            }
//        }

        return redirect()->route('home');
    }

    public function ajax(Request $request, $type = null)
    {
        if ($request->ajax()) {
            $suggestions = [];

            if ($request->has('query')) {
                $query = preg_replace('/\s+/', ' ', trim($request->input('query')));

                if (strlen($query) >= 3) {
                    $type_arr = $this->_searchType($type);
                    $data = $this->_search($query, $type_arr, 'search_ajax');

                    foreach ($type_arr as $data_single) {
                        if (isset($data[$data_single])) {
                            $arr = $data[$data_single];
                            $arr_title = array_pluck($arr, 'title', 'token');
                            $arr_unique = array_unique($arr_title);

                            foreach($arr_unique as $key => $val) {
                                $suggestions[] = ['value' => $val, 'id' => $key, 'data' => ['group' => title_case($data_single)]];
                            }
                        }
                    }
                }
            }

            return response()->json(['suggestions' => $suggestions]);
        }
    }

    private function _searchType($type)
    {
        switch($type) {
            case 'ingredients':
                $type_arr = ['ingredients'];
                break;
            case 'recipes':
                $type_arr = ['recipes'];
                break;
            default:
                $type_arr = ['recipes', 'ingredients'];
                break;
        }

        return $type_arr;
    }

    private function _search($query, $type, $cache = 'search', $limit = 5, $select = ['token', 'title'])
    {
        $data = [];

        if (empty($query) && !is_array($type)) {
            return $data;
        }

        $query_array = explode(' ', $query);
        $query_count = count($query_array);

        $has_recipe = in_array('recipes', $type);
        $has_ingredients = in_array('ingredients', $type);

        if ($has_recipe) {
            $query_recipe = '';
        }

        if ($has_ingredients) {
            $query_ingredient = '';
        }

        for ($i = 0; $i < $query_count; $i++) {
            if ($i) {
                if ($has_recipe && !empty($query_recipe)) {
                    $query_recipe->where('title', 'LIKE', '%'.$query_array[$i].'%');
                }

                if ($has_ingredients && !empty($query_ingredient)) {
                    $query_ingredient->where('title', 'LIKE', '%'.$query_array[$i].'%');
                }
            } else {
                if ($has_recipe) {
                    $query_recipe = Recipe::select($select)->where('title', 'LIKE', '%'.$query_array[$i].'%');
                }

                if ($has_ingredients) {
                    $query_ingredient = Ingredient::select($select)->where('title', 'LIKE', '%'.$query_array[$i].'%');
                }
            }
        }

        if (!empty($query_recipe)) {
            $results_recipe = Cache::remember($cache.'_recipe_QUERY_'.strtolower($query), 60*4, function() use ($query_recipe, $limit) {
                return $query_recipe->orderBy('title')->limit($limit)->get();
            });

            if (!$results_recipe->isEmpty()) {
                $data['recipes'] = $results_recipe;
            }
        }

        if (!empty($query_ingredient)) {
            $results_ingredient = Cache::remember($cache.'_ingredient_QUERY_'.strtolower($query), 60*24, function() use ($query_ingredient, $limit) {
                return $query_ingredient->withDepth()->orderBy('depth')->orderBy('title')->limit($limit)->get();
            });

            if (!$results_ingredient->isEmpty()) {
                $data['ingredients'] = $results_ingredient;
            }
        }

        return $data;
    }
}
