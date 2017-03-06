<?php

use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

$days = 5;

Artisan::command('empty-images-trash', function () use ($days) {
    $now = Carbon::now()->startOfDay();
    $path = public_path('storage/trash_images/');
    $files = File::allFiles($path);

    foreach($files as $file) {
        $modify = Carbon::createFromTimestamp(File::lastModified($file))->startOfDay();

        if ($modify->diffInDays($now, false) >= $days) {
            File::delete($file);
        }
    }
})->describe('Empty images trash after '.$days.' days of moved.');
