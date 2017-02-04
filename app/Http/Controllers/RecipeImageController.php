<?php

namespace App\Http\Controllers;

use Auth;
use App\Http\Requests\ImageRequest;
use App\Recipe;
use App\RecipeImage;
use Illuminate\Http\Request;
use Image;
use Validator;

class RecipeImageController extends Controller
{
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
        if (Auth::check()) {
            if ($request->ajax()) {
                if ($request->has('id') && $request->hasFile('image')) {
                    $validator = Validator::make($request->all(), [
                        'id' => 'required|size:6',
                        'image' => 'required|image|mimes:gif,jpeg,jpg,png|max:8192',
                    ]);

                    if ($validator->passes()) {
                        $user = Auth::user();
                        $token = $request->input('id');
                        $recipe = Recipe::select('id')->token($token)->where('user_id', $user->id)->first();

                        if (!empty($recipe)) {
                            $image = $request->file('image');
//                            $image_extension = $image->extension();
                            $recipe_id = $recipe->id;
                            $token_valid = false;
                            $recipe_image = RecipeImage::firstOrCreate(['recipe_id' => $recipe_id]);

                            do {
                                $random = \Helper::hashids_random($recipe_id, 'image');

                                if (!empty($random)) {
//                                    $filename = $random.'.'.$image_extension;
                                    $filename = $random.'.jpg';
                                    $recipe_image_check = RecipeImage::where('image', 'LIKE BINARY', $filename)->first();

                                    if (empty($recipe_image_check)) {
                                        Image::make($image)->resize(1200, null, function ($constraint) {
                                            $constraint->aspectRatio();
                                            $constraint->upsize();
                                        })->save('upload/'.$filename)->destroy();

                                        if ($recipe_image->update(['image' => $filename])) {
                                            $token_valid = true;
                                        }
                                    }
                                }
                            } while(!$token_valid);

                            if ($token_valid) {
                                return response()->json(['success' => true, 'image' => route('imagecache', ['template' => 'large', 'filename' => $filename])]);
                            }
                        }
                    } else {
                        return response()->json(['error' => $validator->errors()->all()]);
                    }
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
