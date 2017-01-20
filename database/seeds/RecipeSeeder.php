<?php

use Illuminate\Database\Seeder;
use App\Ingredient;
use App\Glass;
use App\Measure;
use App\Recipe;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_glasses = Glass::all( [ 'id', 'slug' ] );
        $glasses      = [ ];
        foreach ($data_glasses as $data_glass) {
            $glasses[$data_glass->slug] = $data_glass->id;
        }

        $data_measures = Measure::all( [ 'id', 'title_abbr' ] );
        $measures      = [ ];
        foreach ($data_measures as $data_measure) {
            $measures[$data_measure->title_abbr] = $data_measure->id;
        }

        $lists = [
            [
                'recipe'      => [
                    'title'       => 'Long Island Iced Tea',
                    'description' => 'Yummy!',
                    'directions'   => ['Mix ingredients together over ice in a glass.', 'Pour into a shaker and give one brisk shake.', 'Pour back into the glass and make sure there is a touch of fizz at the top.', 'Garnish with lemon.'],
                    'glass_id'    => $glasses['collins'],
                    'user_id'     => 1,
                    'is_active'   => 1,
                ],
                'ingredients' => [
                    [ 'slug' => 'vodka', 'measure_id' => 'oz', 'measure_amount' => 1 ],
                    [ 'slug' => 'tequila', 'measure_id' => 'oz', 'measure_amount' => 1 ],
                    [ 'slug' => 'gin', 'measure_id' => 'oz', 'measure_amount' => 1 ],
                    [ 'slug' => 'rum', 'measure_id' => 'oz', 'measure_amount' => 1 ],
                    [ 'slug' => 'triple-sec', 'measure_id' => 'oz', 'measure_amount' => 1 ],
                    [ 'slug' => 'sweet-and-sour-mix', 'measure_id' => 'oz', 'measure_amount' => 1.5 ],
                    [ 'slug' => 'coca-cola', 'measure_id' => 'splash', 'measure_amount' => 1 ],
                ]
            ],
            [
                'recipe'      => [
                    'title'       => 'Caribou Lou',
                    'description' => 'woo!',
                    'directions'   => ['Shake or serve up with ice in a highball glass.'],
                    'glass_id'    => $glasses['highball'],
                    'user_id'     => 1,
                    'is_active'   => 1,
                ],
                'ingredients' => [
                    [ 'slug' => '151-proof-rum', 'measure_id' => 'oz', 'measure_amount' => 1.5 ],
                    [ 'slug' => 'malibu-coconut-rum', 'measure_id' => 'oz', 'measure_amount' => 1 ],
                    [ 'slug' => 'pineapple', 'measure_id' => 'oz', 'measure_amount' => 5 ],
                ]
            ],
            [
                'recipe'      => [
                    'title'       => 'Jager Bomb',
                    'description' => 'Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing!',
                    'directions'   => ['hi', 'woo that\'s amazing!'],
                    'glass_id'    => $glasses['old-fashioned'],
                    'user_id'     => 1,
                    'is_active'   => 1,
                ],
                'ingredients' => [
                    [ 'slug' => 'red-bull', 'measure_id' => 'can', 'measure_amount' => .5 ],
                    [ 'slug' => 'jagermeister', 'measure_id' => 'oz', 'measure_amount' => 2 ],
                ]
            ],
        ];

        foreach ($lists as $list) {

            $count            = 0;
            $ingredients_data = [ ];
            $recipe           = Recipe::create( $list['recipe'] );

            if (!empty($recipe)) {
                $recipe->counts()->create([]);
                $ingredients      = $list['ingredients'];

                foreach ($ingredients as $ingredient) {
                    $data = Ingredient::where( 'slug', '=', $ingredient['slug'] )->first();

                    if ( ! empty( $data )) {
                        $ingredients_data[$data->id] = [
                            'measure_id'     => $measures[$ingredient['measure_id']],
                            'measure_amount' => $ingredient['measure_amount'],
                            'order_by'       => $count++
                        ];
                    }
                }

                $recipe->ingredients()->sync( $ingredients_data );
            }
        }
    }
}
