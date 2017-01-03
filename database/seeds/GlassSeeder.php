<?php

use Illuminate\Database\Seeder;
use App\Glass;

class GlassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $is_active = 1;

        $glasses = [
            'Beer',
            'Collins',
            'Champagne',
            'Highball',
            'Old-Fashioned',
        ];

        foreach ($glasses as $glass) {
            Glass::create(array('title' => $glass, 'user_id' => 1, 'is_active' => $is_active));
        }
    }
}
