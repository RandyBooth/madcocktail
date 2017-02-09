<?php

namespace App\Http\Controllers;

use App\Ingredient;
use App\Recipe;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Http\Requests\SearchAjaxRequest;

class SearchController extends Controller
{
    public function __construct()
    {
        Cache::flush();
    }

    public function test(Request $request) {
        dd($request->all());
    }

    public function search(SearchAjaxRequest $request, $type = null)
    {
        if ($request->ajax()) {
            $suggestions = [];

            if ($request->has('query')) {
                $query = preg_replace('/\s+/', ' ', trim($request->input('query')));

                if (strlen($query) >= 3) {
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

                    $data = $this->_search($query, $type_arr);

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

    private function _search($query, $type)
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
                    $query_recipe = Recipe::select('token', 'title')->where('title', 'LIKE', '%'.$query_array[$i].'%');
                }

                if ($has_ingredients) {
                    $query_ingredient = Ingredient::select('token', 'title')->where('title', 'LIKE', '%'.$query_array[$i].'%');
                }
            }
        }

        if (!empty($query_recipe)) {
            $results_recipe = Cache::tags('search_recipe')->remember(strtolower($query), 30, function() use ($query_recipe) {
                return $query_recipe->orderBy('title')->limit(5)->get();
            });

            if (!$results_recipe->isEmpty()) {
                $data['recipes'] = $results_recipe;
            }
        }

        if (!empty($query_ingredient)) {
            $results_ingredient = Cache::tags('search_ingredient')->remember(strtolower($query), 60, function() use ($query_ingredient) {
                return $query_ingredient->withDepth()->orderBy('depth')->orderBy('title')->limit(5)->get();
            });

            if (!$results_ingredient->isEmpty()) {
                $data['ingredients'] = $results_ingredient;
            }
        }

        return $data;
    }
}
