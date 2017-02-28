<?php

namespace App\Filters\Image;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Share implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        $width = 1200;
        $height = 627;

        return $image->fit($width, $height, function ($constraint) {
            $constraint->upsize();
        })->interlace();
    }
}
