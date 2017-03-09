<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cache::flush();
        // $this->call(UsersTableSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(MeasureSeeder::class);
        $this->call(GlassSeeder::class);
        $this->call(IngredientSeeder::class);
        // $this->call(RecipeSeeder::class);
    }
}
