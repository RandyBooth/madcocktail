<?php

namespace App\Filters\Image;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class ListsTiny implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        $width = 30;
        $height = ceil($width / 1.2);
        if ($height % 2 == 1) $height++;

        return $image->fit($width, $height, function ($constraint) {
            $constraint->upsize();
        })->blur(2)->interlace()->encode('jpg', 20);
    }
}
