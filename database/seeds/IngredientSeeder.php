<?php

use Illuminate\Database\Seeder;
use App\Ingredient;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ingredients = [
            ['title' => 'Beer', 'is_active' => 1, 'is_alcoholic' => 1],
            ['title' => 'Liqueur', 'is_active' => 1, 'is_alcoholic' => 1, 'children' => [
                ['title' => 'Curaçao', 'is_active' => 1, 'children' => [
                    ['title' => 'Triple sec', 'is_active' => 1],
                ]],
                ['title' => 'Gin', 'is_active' => 1],
                ['title' => 'Herbal', 'is_active' => 1, 'children' => [
                    ['title' => 'Jagermeister®', 'is_active' => 1],
                ]],
                ['title' => 'Rum', 'is_active' => 1, 'children' => [
                    ['title' => 'Black spiced rum', 'is_active' => 1, 'children' => [
                        ['title' => 'The Kraken™ Black Spiced Rum', 'is_active' => 1],
                        ['title' => 'Captain Morgan® Black Spiced Rum', 'is_active' => 1],
                    ]],
                    ['title' => 'White rum', 'is_active' => 1, 'children' => [
                        ['title' => '10 Cane', 'is_active' => 1],
                        ['title' => 'Bacardi Superior', 'is_active' => 1],
                        ['title' => 'Bambu', 'is_active' => 1],
                    ]],
                    ['title' => 'Dark rum', 'is_active' => 1],
                    ['title' => 'Overproof rum', 'is_active' => 1, 'children' => [
                        ['title' => '151 proof rum', 'is_active' => 1],
                    ]],
                    ['title' => 'Favored', 'is_active' => 1, 'children' => [
                        ['title' => 'Coconut', 'is_active' => 1, 'children' => [
                            ['title' => 'Malibu® coconut rum', 'is_active' => 1]
                        ]],
                    ]],
                ]],
                ['title' => 'Tequila', 'is_active' => 1],
                ['title' => 'Vodka', 'is_active' => 1],
            ]],
            ['title' => 'Wine', 'is_active' => 1, 'is_alcoholic' => 1],
            ['title' => 'Egg', 'is_active' => 1],
            ['title' => 'Sweet and sour mix', 'is_active' => 1],
            ['title' => 'Juice', 'is_active' => 1, 'children' => [
                ['title' => 'Pineapple', 'is_active' => 1],
            ]],
            ['title' => 'Soda', 'is_active' => 1, 'children' => [
                ['title' => 'Energy', 'is_active' => 1, 'children' => [
                    ['title' => 'Red Bull®', 'is_active' => 1],
                ]],
                ['title' => 'Cola', 'is_active' => 1, 'children' => [
                    ['title' => 'Coca-Cola®', 'is_active' => 1],
                ]],
            ]],
        ];

        Ingredient::rebuildTree($ingredients);
    }
}
