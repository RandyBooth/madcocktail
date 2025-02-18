<?php
namespace App\Helpers;

use App\User;
use File;
//use Helper;
use Image;

class HelperImage
{
    public static function get_gravatar($email, $s = 240, $d = '404', $r = 'g')
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";

        return self::valid_url($url);
    }

    public static function valid_url($url)
    {
        $headers = get_headers($url, 1);

        if ($headers[0] == 'HTTP/1.1 200 OK') {
            return $url;
        }

        return false;
    }

    public static function upload_user_image($image = null, $data = null)
    {
        if (!empty($image)) {
            if (!empty($data->id)) {
                $filename = '';
                $path = public_path('storage/upload_images/');
                $id = $data->id;
                $is_valid = false;

                do {
                    $random = Helper::hashids_random($id, 'user_image');

                    if (!empty($random)) {
                        $filename = $random.'.jpg';
                        $image_check = User::where('image', 'LIKE BINARY', $filename)->first();

                        if (!$image_check) {
                            $new_image = Image::make($image)->resize(400, null, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            })->interlace()->save($path.$filename);

                            $old_image = $data->image;
                            $new_image->destroy();

                            if (File::exists($path.$filename)) {
                                $data->update(['image' => $filename]);
                                if (!empty($old_image)) {
                                    if (File::exists($path.$old_image)) {
                                        $moved_image = public_path('storage/trash_images/'.$old_image);

                                        if (File::move($path.$old_image, $moved_image)) {
                                            touch($moved_image);
                                        }
                                    }
                                }
                            }

                            $is_valid = true;
                        }
                    }
                } while(!$is_valid);

                return $filename;
            }
        }

        return false;
    }
}
