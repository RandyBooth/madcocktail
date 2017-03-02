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
                    'user_id'     => 2,
                    'is_active'   => 1,
                ],
                'ingredients' => [
                    [ 'slug' => 'vodka', 'measure_abbr' => 'oz', 'measure_amount' => 1 ],
                    [ 'slug' => 'tequila', 'measure_abbr' => 'oz', 'measure_amount' => 1 ],
                    [ 'slug' => 'gin', 'measure_abbr' => 'oz', 'measure_amount' => 1 ],
                    [ 'slug' => 'rum', 'measure_abbr' => 'oz', 'measure_amount' => 1 ],
                    [ 'slug' => 'triple-sec', 'measure_abbr' => 'oz', 'measure_amount' => 1 ],
                    [ 'slug' => 'sweet-and-sour-mix', 'measure_abbr' => 'oz', 'measure_amount' => 1.5 ],
                    [ 'slug' => 'coca-cola', 'measure_abbr' => 'splash', 'measure_amount' => 1 ],
                ]
            ],
            [
                'recipe'      => [
                    'title'       => 'Caribou Lou',
                    'description' => 'woo!',
                    'directions'   => ['Shake or serve up with ice in a highball glass.'],
                    'glass_id'    => $glasses['highball'],
                    'user_id'     => 2,
                    'is_active'   => 1,
                ],
                'ingredients' => [
                    [ 'slug' => '151-proof-rum', 'measure_abbr' => 'oz', 'measure_amount' => 1.5 ],
                    [ 'slug' => 'malibu-coconut-rum', 'measure_abbr' => 'oz', 'measure_amount' => 1 ],
                    [ 'slug' => 'pineapple', 'measure_abbr' => 'oz', 'measure_amount' => 5 ],
                ]
            ],
            [
                'recipe'      => [
                    'title'       => 'Jager Bomb',
                    'description' => 'Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing! Amazing!',
                    'directions'   => ['Pour red bull into a medium sized glass.', 'Add a shot glass of jagermeister, and chug.'],
                    'glass_id'    => $glasses['old-fashioned'],
                    'user_id'     => 2,
                    'is_active'   => 1,
                ],
                'ingredients' => [
                    [ 'slug' => 'red-bull', 'measure_abbr' => 'can', 'measure_amount' => .5 ],
                    [ 'slug' => 'jagermeister', 'measure_abbr' => 'oz', 'measure_amount' => 2 ],
                ]
            ],
            [
                'recipe'      => [
                    'title'       => 'Test',
                    'description' => 'Testing',
                    'directions'   => ['Open the bottle.', 'Drink it up!'],
                    'glass_id'    => $glasses['beer'],
                    'user_id'     => 2,
                    'is_active'   => 1,
                ],
                'ingredients' => [
                    [ 'slug' => 'rum', 'measure_abbr' => 'oz', 'measure_amount' => 20.777777 ],
                    [ 'slug' => 'triple-sec', 'measure_abbr' => 'oz', 'measure_amount' => 120 ],
                    [ 'slug' => 'gin', 'measure_abbr' => 'oz', 'measure_amount' => 20.99999999 ],
                    [ 'slug' => 'herbal', 'measure_abbr' => 'oz', 'measure_amount' => 20.523525245 ],
                    [ 'slug' => 'jagermeister', 'measure_abbr' => 'oz', 'measure_amount' => 20.88358383838 ],
                    [ 'slug' => 'black-spiced-rum', 'measure_abbr' => 'oz', 'measure_amount' => 20.1111111 ],
                    [ 'slug' => '10-cane', 'measure_abbr' => 'oz', 'measure_amount' => 20.44444444 ],
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
                        $ingredient_arr = [
                            'measure_amount' => $ingredient['measure_amount'],
                            'order_by'       => $count++
                        ];

                        if (isset($ingredient['measure_abbr'])) {
                            $ingredient_arr['measure_id'] = $measures[$ingredient['measure_abbr']];
                        }

                        $ingredients_data[$data->id] = $ingredient_arr;
                    }
                }

                $recipe->ingredients()->sync( $ingredients_data );
            }
        }
    }
}
