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
            [
                'title'        => 'Beer',
                'is_active'    => 1,
                'is_alcoholic' => 1,
                'children'     => [
                    [
                        'title'     => 'Ale',
                        'is_active' => 1,
                        'children'  => [
                            [ 'title' => 'Barley wine', 'is_active' => 1 ],
                            [ 'title' => 'Belgian', 'is_active' => 1 ],
                            [ 'title' => 'Bitter ale', 'is_active' => 1 ],
                            [ 'title' => 'Brown ale', 'is_active' => 1 ],
                            [ 'title' => 'Burton ale', 'is_active' => 1 ],
                            [ 'title' => 'Cask ale', 'is_active' => 1 ],
                            [ 'title' => 'Golden ale', 'is_active' => 1 ],
                            [ 'title' => 'India Pale Ale', 'is_active' => 1 ],
                            [ 'title' => 'Mild ale', 'is_active' => 1 ],
                            [ 'title' => 'Old ale', 'is_active' => 1 ],
                            [
                                'title'     => 'Pale ale',
                                'is_active' => 1
                            ],
                            [
                                'title'     => 'Porter',
                                'is_active' => 1,
                                'children'  => [
                                    [ 'title' => 'Stout', 'is_active' => 1 ],
                                ]
                            ],
                            [ 'title' => 'Scotch ale', 'is_active' => 1 ],
                        ]
                    ],
                    [
                        'title'     => 'Fruit beer',
                        'is_active' => 1,
                    ],
                    [
                        'title'     => 'Lager',
                        'is_active' => 1,
                        'children'  => [
                            [
                                'title'     => 'Pale ale',
                                'is_active' => 1,
                                'children'  => [
                                    [ 'title' => 'Bock', 'is_active' => 1 ],
                                    [ 'title' => 'Oktoberfest', 'is_active' => 1 ],
                                    [ 'title' => 'Pilsener', 'is_active' => 1 ],
                                    [ 'title' => 'Dortmunder Export', 'is_active' => 1 ],
                                    [ 'title' => 'Helles', 'is_active' => 1 ],
                                    [ 'title' => 'American lager', 'is_active' => 1 ],
                                    [ 'title' => 'Dry beer', 'is_active' => 1 ],
                                    [ 'title' => 'Premium lager', 'is_active' => 1 ],
                                    [ 'title' => 'Malt liquor', 'is_active' => 1 ],
                                    [ 'title' => 'Märzen', 'is_active' => 1 ],
                                ]
                            ],
                            [
                                'title'     => 'Pale ale',
                                'is_active' => 1,
                                'children'  => [
                                    [ 'title' => 'Scotch ale', 'is_active' => 1 ],
                                ]
                            ],
                            [ 'title' => 'Scotch ale', 'is_active' => 1 ],
                        ]
                    ],
                    [
                        'title'     => 'Sahti',
                        'is_active' => 1,
                    ],
                    [
                        'title'     => 'Wheat',
                        'is_active' => 1,
                        'children' => [
                            [
                                'title'     => 'Weizenbier',
                                'is_active' => 1,
                            ],
                            [
                                'title'     => 'Witbier',
                                'is_active' => 1,
                            ],
                        ]
                    ],
                ]
            ],
            [
                'title'        => 'Spirits',
                'is_active'    => 1,
                'is_alcoholic' => 1,
                'children'     => [
                    [ 'title' => 'Absinthe', 'is_active' => 1 ],
                    [ 'title' => 'Akvavit', 'is_active' => 1 ],
                    [ 'title' => 'Applejack', 'is_active' => 1 ],
                    [ 'title' => 'Arak', 'is_active' => 1 ],
                    [ 'title' => 'Arrack', 'is_active' => 1 ],
                    [ 'title' => 'Awamori', 'is_active' => 1 ],
                    [ 'title' => 'Baijiu', 'is_active' => 1 ],
                    [ 'title' => 'Borovička', 'is_active' => 1 ],
                    [ 'title' => 'Brandy', 'is_active' => 1, 'children' => [
                        [ 'title' => 'Armenian brandy', 'is_active' => 1 ],
                        [ 'title' => 'Armagnac', 'is_active' => 1 ],
                        [ 'title' => 'Cognac', 'is_active' => 1 ],
                        [ 'title' => 'Cyprus brandy', 'is_active' => 1 ],
                        [ 'title' => 'Greek brandy', 'is_active' => 1 ],
                        [ 'title' => 'Brandy de Jerez', 'is_active' => 1 ],
                        [ 'title' => 'Kanyak', 'is_active' => 1 ],
                        [ 'title' => 'Pisco', 'is_active' => 1 ],
                        [ 'title' => 'Stravecchio', 'is_active' => 1 ],

                    ]],
                    [ 'title' => 'Cachaça', 'is_active' => 1 ],
                    [ 'title' => 'Gin', 'is_active' => 1 ],
                    [
                        'title'     => 'Herbal',
                        'is_active' => 1,
                        'children'  => [
                            [ 'title' => 'Jagermeister®', 'is_active' => 1 ],
                        ]
                    ],
                    [ 'title' => 'Horilka', 'is_active' => 1 ],
                    [ 'title' => 'Kaoliang', 'is_active' => 1 ],
                    [ 'title' => 'Maotai', 'is_active' => 1 ],
                    [ 'title' => 'Metaxa', 'is_active' => 1 ],
                    [ 'title' => 'Mezcal', 'is_active' => 1 ],
                    [ 'title' => 'Neutral grain spirit', 'is_active' => 1 ],
                    [ 'title' => 'Ogogoro', 'is_active' => 1 ],
                    [ 'title' => 'Palinka', 'is_active' => 1 ],
                    [ 'title' => 'Pisco', 'is_active' => 1 ],
                    [ 'title' => 'Poitín', 'is_active' => 1 ],
                    [ 'title' => 'Rakı', 'is_active' => 1 ],
                    [ 'title' => 'Rakia', 'is_active' => 1 ],
                    [
                        'title'     => 'Rum',
                        'is_active' => 1,
                        'children'  => [
                            [
                                'title'     => 'Black spiced rum',
                                'is_active' => 1,
                                'children'  => [
                                    [ 'title' => 'The Kraken™ Black Spiced Rum', 'is_active' => 1 ],
                                    [ 'title' => 'Captain Morgan® Black Spiced Rum', 'is_active' => 1 ],
                                ]
                            ],
                            [ 'title' => 'Dark rum', 'is_active' => 1 ],
                            [
                                'title'     => 'Favored',
                                'is_active' => 1,
                                'children'  => [
                                    [
                                        'title'     => 'Coconut',
                                        'is_active' => 1,
                                        'children'  => [
                                            [ 'title' => 'Malibu® coconut rum', 'is_active' => 1 ]
                                        ]
                                    ],
                                ]
                            ],
                            [ 'title' => 'Gold rum', 'is_active' => 1 ],
                            [ 'title' => 'Light rum', 'is_active' => 1 ],
                            [ 'title' => 'Premium rum', 'is_active' => 1 ],
                            [
                                'title'     => 'Overproof rum',
                                'is_active' => 1,
                                'children'  => [
                                    [ 'title' => '151 proof rum', 'is_active' => 1 ],
                                ]
                            ],
                            [ 'title' => 'Spiced rum', 'is_active' => 1 ],
                            [
                                'title'     => 'White rum',
                                'is_active' => 1,
                                'children'  => [
                                    [ 'title' => '10 Cane', 'is_active' => 1 ],
                                    [ 'title' => 'Bacardi Superior', 'is_active' => 1 ],
                                    [ 'title' => 'Bambu', 'is_active' => 1 ],
                                ]
                            ],
                        ]
                    ],
                    [ 'title' => 'Shochu', 'is_active' => 1 ],
                    [ 'title' => 'Singani', 'is_active' => 1 ],
                    [ 'title' => 'Soju', 'is_active' => 1 ],
                    [ 'title' => 'Ţuică', 'is_active' => 1 ],
                    [ 'title' => 'Tequila', 'is_active' => 1, 'children' => [
                        [ 'title' => 'Añejo', 'is_active' => 1 ],
                        [ 'title' => 'Extra Añejo', 'is_active' => 1 ],
                        [ 'title' => 'Gold Tequila', 'is_active' => 1 ],
                        [ 'title' => 'Reposado', 'is_active' => 1 ],
                        [ 'title' => 'Silver Tequila', 'is_active' => 1 ],

                    ]],
                    [ 'title' => 'Vodka', 'is_active' => 1, 'children' => [
                        [ 'title' => 'Flavored', 'is_active' => 1 ],
                        [ 'title' => 'Poland', 'is_active' => 1 ],
                        [ 'title' => 'Russia', 'is_active' => 1 ],
                        [ 'title' => 'Sweden', 'is_active' => 1 ],

                    ] ],
                    [ 'title' => 'Whiskey', 'is_active' => 1, 'children' => [
                        [ 'title' => 'Malt whisky', 'is_active' => 1 ],
                        [ 'title' => 'Grain whisky', 'is_active' => 1 ],
                        [ 'title' => 'Single malt whisky', 'is_active' => 1 ],
                        [ 'title' => 'Blended malt whisky', 'is_active' => 1 ],
                        [ 'title' => 'Blended whisky', 'is_active' => 1 ],
                        [ 'title' => 'Cask strength', 'is_active' => 1 ],
                        [ 'title' => 'Single cask', 'is_active' => 1 ],
                        [ 'title' => 'Bourbon whiskey', 'is_active' => 1 ],
                        [ 'title' => 'Corn whiskey', 'is_active' => 1 ],
                        [ 'title' => 'Malt whiskey', 'is_active' => 1 ],
                        [ 'title' => 'Rye whiskey', 'is_active' => 1 ],
                        [ 'title' => 'Rye malt whiskey', 'is_active' => 1 ],
                        [ 'title' => 'Wheat whiskey', 'is_active' => 1 ],
                        [ 'title' => 'Australian whisky', 'is_active' => 1 ],
                        [ 'title' => 'Canadian whisky', 'is_active' => 1 ],
                        [ 'title' => 'Danish whisky', 'is_active' => 1 ],
                        [ 'title' => 'English whisky', 'is_active' => 1 ],
                        [ 'title' => 'Finnish whisky', 'is_active' => 1 ],
                        [ 'title' => 'German whisky', 'is_active' => 1 ],
                        [ 'title' => 'Indian whisky', 'is_active' => 1 ],
                        [ 'title' => 'Irish whisky', 'is_active' => 1 ],
                        [ 'title' => 'Japanese whisky', 'is_active' => 1 ],
                        [ 'title' => 'Scotch whisky', 'is_active' => 1 ],
                        [ 'title' => 'Swedish whisky', 'is_active' => 1 ],
                        [ 'title' => 'Taiwanese whisky', 'is_active' => 1 ],
                        [ 'title' => 'Welsh whisky', 'is_active' => 1 ],
                        [ 'title' => 'Australian whisky', 'is_active' => 1 ],
                        [ 'title' => 'Australian whisky', 'is_active' => 1 ],

                    ]],
                    [
                        'title'     => 'Curaçao',
                        'is_active' => 1,
                        'children'  => [
                            [ 'title' => 'Triple sec', 'is_active' => 1 ],
                        ]
                    ],
                ]
            ],
            [ 'title' => 'Wine', 'is_active' => 1, 'is_alcoholic' => 1,
                'children' => [
                    [ 'title' => 'Fortified wine', 'is_active' => 1, 'children' => [
                        [ 'title' => 'Port', 'is_active' => 1 ],
                        [ 'title' => 'Madeira', 'is_active' => 1 ],
                        [ 'title' => 'Marsala', 'is_active' => 1 ],
                        [ 'title' => 'Sherry', 'is_active' => 1 ],
                        [ 'title' => 'Vermouth', 'is_active' => 1 ],
                        [ 'title' => 'Vinsanto', 'is_active' => 1 ],

                    ]],
                    [ 'title' => 'Fruit wine', 'is_active' => 1 ],
                    [ 'title' => 'Table wine', 'is_active' => 1 ],
                    [ 'title' => 'Sparkling wine', 'is_active' => 1, 'children' => [
                        [ 'title' => 'Champagne', 'is_active' => 1 ],
                        [ 'title' => 'Semi-sparkling wine', 'is_active' => 1 ],
                        [ 'title' => 'Red sparkling wine', 'is_active' => 1 ],
                        [ 'title' => 'French sparkling wine', 'is_active' => 1 ],
                        [ 'title' => 'Cava', 'is_active' => 1 ],
                        [ 'title' => 'Portuguese sparkling wine', 'is_active' => 1 ],
                        [ 'title' => 'Italian sparkling wine', 'is_active' => 1 ],
                        [ 'title' => 'Sekt', 'is_active' => 1 ],
                        [ 'title' => 'Pezsgő', 'is_active' => 1 ],
                        [ 'title' => 'Sovetskoye Shampanskoye', 'is_active' => 1 ],
                        [ 'title' => 'Romanian sparkling wine', 'is_active' => 1 ],
                        [ 'title' => 'English sparkling wine', 'is_active' => 1 ],
                        [ 'title' => 'American sparkling wine', 'is_active' => 1 ],
                        [ 'title' => 'Canada sparkling wine', 'is_active' => 1 ],
                        [ 'title' => 'Australia sparkling wine', 'is_active' => 1 ],
                        [ 'title' => 'Chile sparkling wine', 'is_active' => 1 ],
                        [ 'title' => 'South Africa sparkling wine', 'is_active' => 1 ],
                    ]],
                ]
            ],
            [ 'title' => 'Egg', 'is_active' => 1 ],
            [ 'title' => 'Sweet and sour mix', 'is_active' => 1 ],
            [
                'title'     => 'Juice',
                'is_active' => 1,
                'children'  => [
                    [ 'title' => 'Pineapple', 'is_active' => 1 ],
                ]
            ],
            [
                'title'     => 'Soda',
                'is_active' => 1,
                'children'  => [
                    [
                        'title'     => 'Energy',
                        'is_active' => 1,
                        'children'  => [
                            [ 'title' => 'Red Bull®', 'is_active' => 1 ],
                        ]
                    ],
                    [
                        'title'     => 'Cola',
                        'is_active' => 1,
                        'children'  => [
                            [ 'title' => 'Coca-Cola®', 'is_active' => 1 ],
                        ]
                    ],
                ]
            ],
        ];

        Ingredient::rebuildTree($ingredients);
    }
}
