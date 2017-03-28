<?php

namespace App\Filters\Image;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class UserNormal implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        $width = 250;
        $height = $width;

        return $image->fit($width, $height, function ($constraint) {
            $constraint->upsize();
        })->interlace();
    }
}
