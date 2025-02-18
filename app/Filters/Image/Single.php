<?php

namespace App\Filters\Image;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Single implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        $width = 600;
        $height = ceil($width / 1.8);
        if ($height % 2 == 1) $height++;

        return $image->fit($width, $height, function ($constraint) {
            $constraint->upsize();
        })->interlace();
    }
}
