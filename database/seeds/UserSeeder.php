<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'email' => 'Randy.Booth@gmail.com',
            'password' => bcrypt('11111111'),
            'role' => 1,
            'is_active' => 1
        ]);
    }
}
