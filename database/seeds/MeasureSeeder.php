<?php

use Illuminate\Database\Seeder;
use App\Measure;

class MeasureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $is_active = 1;

        $measures = [
            ['', '', 0, 0],
            ['Can', 'can', 0, 0],
            ['Cup', 'cup', 236.58, 8],
            ['Dash', 'dash', 0.92, 0.031],
            ['Fifth', 'fifth', 757.08, 25.6],
            ['Fluid Ounce', 'fl oz', 29.57, 1],
            ['Gallon', 'gal', 3785.41, 128],
            ['Jigger', 'jigger', 44.36, 1.5],
            ['Liter', 'l', 1000, 33.81],
            ['Milliliter', 'ml', 1, 0.034],
            ['Ounce', 'oz', 29.57, 1],
            ['Part', 'part', 0, 0],
            ['Pint', 'pt', 473.18, 16],
            ['Pony', 'pony', 29.57, 1],
            ['Quart', 'qt', 946.35, 32],
            ['Shot', 'shot', 44.36, 1.5],
            ['Splash', 'splash', 2.45, 0.083],
            ['Tablespoon', 'tbsp', 14.79, .5],
            ['Teaspoon', 'tsp', 4.91, 0.166],
        ];

        foreach ($measures as $measure) {
            Measure::create(array('title' => $measure[0], 'title_abbr' => $measure[1], 'measurement_ml' => $measure[2], 'measurement_oz' => $measure[3], 'user_id' => 1, 'is_active' => $is_active));
        }
    }
}
