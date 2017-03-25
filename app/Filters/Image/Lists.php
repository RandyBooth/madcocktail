<?php

namespace App\Filters\Image;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Lists implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        $width = 400;
        $height = ceil($width / 1.6);
        if ($height % 2 == 1) $height++;

        return $image->fit($width, $height, function ($constraint) {
            $constraint->upsize();
        })->interlace();
    }
}
