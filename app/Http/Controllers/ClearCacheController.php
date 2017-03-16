<?php

namespace App\Http\Controllers;

use Cache;

class ClearCacheController extends Controller
{
    public function __construct()
    {
        $this->middleware(['admin', 'isVerified', 'user-valid']);
    }

    public function all()
    {
        Cache::flush();
        echo 'Clear!';
    }

    public function glasses()
    {
        Cache::forget('glasses');
        echo 'Clear!';
    }

    public function measures()
    {
        Cache::forget('measures');
        echo 'Clear!';
    }
}
