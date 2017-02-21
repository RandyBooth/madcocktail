<?php

namespace App\Filters\Image;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Tiny implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->fit(30, 15, function ($constraint) {
            $constraint->upsize();
        })->blur()->interlace()->encode('jpg', 20);
    }
}
