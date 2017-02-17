<?php

namespace App\Filters\Image;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Show implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->fit(600, 300, function ($constraint) {
            $constraint->upsize();
        })->interlace();
    }
}