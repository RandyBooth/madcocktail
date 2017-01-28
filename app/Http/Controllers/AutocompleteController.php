<?php

namespace App\Http\Controllers;

use App\Ingredient;
use App\Recipe;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Http\Requests\AutocompleteAjaxRequest;

class AutocompleteController extends Controller
{
    public function __construct()
    {
//        Cache::flush();
    }

    public function search(AutocompleteAjaxRequest $request)
    {
        if ($request->ajax()) {
            $suggestions = [];

            if ($request->has('query')) {
                $data = $this->_search($request->input('query'));
                $data_each = ['recipes', 'ingredients'];

                foreach ($data_each as $data_single) {
                    if (isset($data[$data_single])) {
                        $arr = $data[$data_single];
                        $arr_id = array_pluck($arr, 'id');
                        $arr_title = array_pluck($arr, 'title', 'id');
                        $arr_unique = array_unique($arr_title);

                        foreach($arr_unique as $val) {
                            $suggestions[] = ['value' => $val, 'data' => ['category' => title_case(str_singular($data_single))]];
                        }
                    }
                }
            }

            return response()->json(['suggestions' => $suggestions]);
        }
    }

    private function _search($query)
    {
        $data = [];
        $query = preg_replace('/\s+/', ' ', trim($query));
        $query_array = explode(' ', $query);
        $query_count = count($query_array);

        $query_recipe = '';
        $query_ingredient = '';

        for ($i = 0; $i < $query_count; $i++) {
            if ($i) {
                $query_recipe->where('title', 'LIKE', '%'.$query_array[$i].'%');
                $query_ingredient->where('title', 'LIKE', '%'.$query_array[$i].'%');
            } else {
                $query_recipe = Recipe::where('title', 'LIKE', '%'.$query_array[$i].'%');
                $query_ingredient = Ingredient::where('title', 'LIKE', '%'.$query_array[$i].'%');
            }
        }

        $results_recipe = Cache::tags('search_recipe')->remember(strtolower($query), 60, function() use ($query_recipe) {
            return $query_recipe->orderBy('title')->limit(10)->get();
        });

        $results_ingredient = Cache::tags('search_ingredient')->remember(strtolower($query), 60, function() use ($query_ingredient) {
            return $query_ingredient->orderBy('level')->orderBy('title')->limit(10)->get();
        });

        if (!empty($results_recipe)) {
            $data['recipes'] = $results_recipe;
        }

        if (!empty($results_ingredient)) {
            $data['ingredients'] = $results_ingredient;
        }

        return $data;
    }
}
