<?php

namespace App\Http\Controllers;

use App\Recipe;
use App\User;
use App\UserFavoriteRecipe;
use Auth;
use Cache;
use Carbon\Carbon;
use Helper;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function __construct()
    {
//        $this->middleware(['auth', 'user-valid'], ['only' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (empty($user->username)) {
                return redirect()->route('user-settings.index.edit')->with('danger', Helper::user_valid());
            }
        }

        $user = User::inRandomOrder()->where('username', '<>', '')->where('role', 0)->first();

        if (!empty($user->username)) {
            return redirect()->route('user-profile.show', $user->username);
        }

        abort(404);
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
     * @param  string  $username
     * @return \Illuminate\Http\Response
     */
    public function show($username)
    {
        $user = Cache::remember('user_USERNAME_'.strtolower($username), 1440, function () use ($username) {
            return User::where('username', $username)->firstOrFail();
        });

        $user_settings = Cache::remember('usersettings_ID_'.$user->id, 1440, function () use ($user) {
            return $user->settings()->firstOrCreate([]);
        });

        $recipes = Cache::remember('user_recipes_ID_'.$user->id, 1440, function () use ($user) {
            return Recipe
                ::leftJoin('recipe_images', 'recipes.id', '=', 'recipe_images.recipe_id')
                ->join('users', 'recipes.user_id', '=', 'users.id')
                ->select(['recipes.id', 'recipes.token', 'recipes.title', 'recipes.slug', 'recipes.description', 'recipes.directions', 'recipe_images.image', 'users.id as user_id', 'users.image as user_image', 'users.display_name as user_display_name', 'users.username as username'])
                ->where('user_id', $user->id)
                ->orderby('recipes.created_at', 'DESC')
                ->orderby('recipes.title')
//                ->take(24)
                ->get();
        });

        $favorite_recipes = collect([]);

        if (!$recipes->isEmpty()) {
            if (Auth::check()) {
                $user_id = Auth::id();
                $recipe_id = array_pluck($recipes, 'id');
                $now = Carbon::now()->second(0);
                $expiresAtMinute = $now->copy()->addMinute();

                $favorite_recipes = Cache::remember('recipes_profile_favorite_USERID_'.$user_id, $expiresAtMinute, function () use ($user_id, $recipe_id) {
                    return UserFavoriteRecipe::select('recipe_id')->where('user_id', $user_id)->whereIn('recipe_id', $recipe_id)/*->take(24)*/->pluck(null, 'recipe_id');
                });
            }
        }

        return view('user-profiles.show', compact('user', 'user_settings', 'recipes', 'favorite_recipes'));
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
