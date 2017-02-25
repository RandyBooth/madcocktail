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
            'Aperitif',
            'Beer',
            'Beer Mug',
            'Bottle',
            'Bucket',
            'Champagne',
            'Champagne Flute',
            'Champagne Saucer',
            'Champagne Tulip',
            'Coffee Mug',
            'Collins',
            'Cordial',
            'Coupe',
            'Cup',
            'Highball',
            'Hurricane',
            'Irish Coffee Mug',
            'Jug',
            'Margarita',
            'Martini',
            'Mason Jar',
            'Mint Julep Cup',
            'Moscow Mule Mug',
            'Mug',
            'Old-Fashioned',
            'Parfait',
            'Pilsner',
            'Pint',
            'Pitcher',
            'Poco Grande',
            'Pousse-café',
            'Punch Bowl',
            'Punch Cup',
            'Rocks',
            'Sherry',
            'Shot',
            'Snifter',
            'Test Tube',
            'Tumblers',
            'Wine',
        ];

        foreach ($glasses as $glass) {
            Glass::create(array('title' => $glass, 'user_id' => 1, 'is_active' => $is_active));
        }
    }
}
