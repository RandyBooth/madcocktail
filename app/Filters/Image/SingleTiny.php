<?php

namespace App\Filters\Image;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class SingleTiny implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->resize(30, null, function ($constraint) {
            $constraint->aspectRatio();
        })->blur()->interlace()->encode('jpg', 20);
    }
}
