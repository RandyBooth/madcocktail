<?php

namespace App\Filters\Image;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class UserProfile implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        $width = 100;
        $height = $width;

        return $image->fit($width, $height, function ($constraint) {
            $constraint->upsize();
        })->interlace();
    }
}
