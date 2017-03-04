<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserProfileController extends Controller
{
    public function __construct()
    {
        //Cache::flush();
//        $this->middleware(['auth', 'isVerified'], ['only' => ['create', 'edit']]);
//        $this->middleware(['xss', 'isVerified'], ['only' => ['store', 'update']]);
//        $this->middleware(['admin', 'isVerified'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
        $user = User::where('username', $username)->firstOrFail();

        $recipes = Cache::remember('user_recipes_ID_'.$user->id, 43200, function () use ($user) {
            return Recipe
                ::leftJoin('recipe_images', 'recipes.id', '=', 'recipe_images.recipe_id')
//                ->select(['title', 'slug'])
                ->where('user_id', $user->id)
                ->orderby('recipes.created_at', 'DESC')
                ->orderby('recipes.title')
//                ->take(20)
                ->get();
        });

        return view('user-profiles.show', compact('user', 'recipes'));
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
