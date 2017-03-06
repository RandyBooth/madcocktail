<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\ImageRequest;
use App\Recipe;
use App\RecipeImage;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Image;
use Validator;

class RecipeImageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isVerified', 'xss'], ['only' => ['store']]);
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
        $response = [];
        $response['success'] = false;

        if (Auth::check()) {
            if ($request->ajax()) {
                if ($request->has('id') && $request->hasFile('image')) {
                    $validator = Validator::make($request->all(), [
                        'id' => 'required|min:12|max:20',
                        'image' => 'required|file|image|mimes:gif,jpeg,jpg,png|max:2048',
                    ]);

                    if ($validator->passes()) {
                        $user = Auth::user();
                        $id = $request->input('id');

                        $recipe = Cache::remember('recipe_TOKEN_'.$id, 43200, function () use ($id) {
                            return Recipe::token($id)->with('ingredients')->firstOrFail();
                        });

                        if ($recipe) {
                            if (Helper::is_owner($recipe->user_id)) {

//                                $path = 'upload/';
                                $path = public_path('storage/upload_images/');
                                $recipe_id = $recipe->id;
                                $filename = '';
                                $image = $request->file('image');
                                $token_valid = false;
                                $recipe_image = Cache::remember('recipe_image_ID_'.$recipe_id, 43200, function () use ($recipe_id) {
                                    return RecipeImage::firstOrCreate(['recipe_id' => $recipe_id]);
                                });

                                do {
                                    $random = \Helper::hashids_random($recipe_id, 'image');

                                    if (!empty($random)) {
                                        $filename = $random.'.jpg';
                                        $recipe_image_check = RecipeImage::where('image', 'LIKE BINARY', $filename)->first();

                                        if (!$recipe_image_check) {
                                            $new_image = Image::make($image)->resize(1200, null, function ($constraint) {
                                                $constraint->aspectRatio();
                                                $constraint->upsize();
                                            })->interlace()->save($path.$filename);

                                            $old_image = $recipe_image->image;
                                            $color = $this->getColorAverage($new_image);
                                            $new_image->destroy();

                                            if ($recipe_image->update(['image' => $filename, 'color' => $color])) {
                                                if (!empty($old_image)) {
                                                    if (File::exists($path.$old_image)) {
                                                        $moved_image = public_path('storage/trash_images/'.$old_image);

                                                        if (File::move($path.$old_image, $moved_image)) {
                                                            touch($moved_image);
                                                        }
                                                    }
                                                }

                                                $this->clear($recipe);

                                                $token_valid = true;
                                                $response['message'] = 'Recipe image updated!';
                                            }
                                        }
                                    }
                                } while(!$token_valid);

                                $response['success'] = true;
                                $response['image'] = route('imagecache', ['template' => 'single', 'filename' => $filename]);
                            } else {
                                $response['message'] = 'You are not allow to do that.';
                            }
                        } else {
                            $response['message'] = 'Recipe not found.';
                        }
                    } else {
                        $response['message'] = $validator->errors()->all();
                    }
                } else {
                    $response['message'] = 'Could not create image.';
                }
            } else {
                $response['message'] = 'Is not ajax request.';
            }
        } else {
            $response['message'] = 'Please login.';
        }

        return response()->json($response);
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

    private function getColorAverage($image)
    {
        $image = clone $image;
        $color = $image->limitColors(1)->pickColor(0, 0, 'hex');
        $image->destroy();

        return $color;
    }

    private function clear($recipe)
    {
        if ($recipe) {
            Cache::forget('recipe_image_ID_'.$recipe->id);
            Cache::forget('user_recipes_ID_'.$recipe->user_id);
        }
    }
}
