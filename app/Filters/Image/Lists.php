<?php

namespace App\Filters\Image;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Lists implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->fit(400, 200, function ($constraint) {
            $constraint->upsize();
        })->interlace();
    }
}
