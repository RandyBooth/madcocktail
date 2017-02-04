<?php

namespace App\Filters\Image;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Tiny implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->resize(30, null, function ($constraint) {
            $constraint->aspectRatio();
        })->blur()->encode('jpg', 20);
    }
}
