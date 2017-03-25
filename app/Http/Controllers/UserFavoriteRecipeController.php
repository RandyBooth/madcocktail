<?php

namespace App\Http\Controllers;

use App\Recipe;
use App\UserFavoriteRecipe;
use Auth;
use Cache;
use Illuminate\Http\Request;
use Validator;

class UserFavoriteRecipeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'xss']);
    }

    public function store(Request $request)
    {
        $response = [];
        $response['success'] = false;

        if (Auth::check()) {
            if ($request->ajax()) {
                if ($request->has('id')) {
                    $validator = Validator::make($request->all(), [
                        'id' => 'required|min:12|max:20',
                    ]);

                    if ($validator->passes()) {
                        $id = $request->input('id');

                        $recipe = Cache::remember('recipe_TOKEN_'.$id, 1440, function () use ($id) {
                            return Recipe::token($id)->with('ingredients')->first();
                        });

                        if ($recipe) {
                            $user_id = Auth::id();
                            $recipe_id = $recipe->id;
                            $favorite = UserFavoriteRecipe::withTrashed()->where('user_id', $user_id)->where('recipe_id', $recipe_id)->first();

                            $response['on'] = true;

                            if ($favorite) {
                                if (!empty($favorite->deleted_at)) {
                                    $favorite->restore();
                                } else {
                                    $favorite->delete();
                                    $response['on'] = false;
                                }
                            } else {
                                UserFavoriteRecipe::create(['user_id' => $user_id, 'recipe_id' => $recipe_id]);
                            }

                            self::clear($recipe_id);

                            $response['success'] = true;
                        } else {
                            $response['message'] = 'Recipe not found.';
                        }
                    } else {
                        $response['message'] = $validator->errors()->all();
                    }
                } else {
                    $response['message'] = 'ID could not find.';
                }
            } else {
                $response['message'] = 'Is not ajax request.';
            }
        } else {
            $response['message'] = 'Please login.';
        }

        return response()->json($response);
    }

    private function clear($id = null)
    {
        if (Auth::check()) {
            if (!empty($id)) {
                $user_id = Auth::id();

                Cache::forget('recipe_show_favorite_RECIPE_USER_'.$id.'_'.$user_id);
                Cache::forget('recipes_profile_favorite_USERID_'.$user_id);

                if (Cache::has('recipes_latest')) {
                    $data = Cache::get('recipes_latest');

                    $has_recipe = $data
                        ->pluck('id')
                        ->filter(function ($value) {
                            return $value != null;
                        })
                        ->contains($id);

                    if ($has_recipe) {
                        Cache::forget('recipes_latest_favorite_USERID_'.$user_id);
                    }
                }
                
                if (Cache::has('recipes_home')) {
                    $data = Cache::get('recipes_home');

                    $has_recipe = $data
                        ->pluck('id')
                        ->filter(function ($value) {
                            return $value != null;
                        })
                        ->contains($id);

                    if ($has_recipe) {
                        Cache::forget('recipes_home_favorite_USERID_'.$user_id);
                    }
                }
            }
        }
    }
}
