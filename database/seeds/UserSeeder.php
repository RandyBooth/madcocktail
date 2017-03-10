<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'username' => 'admin',
                'email' => 'admin@madcocktail.com',
                'password' => Hash::make(str_random(60)),
                'birth' => '1984-09-17',
                'role' => 1,
                'verified' => 1,
            ], [
                'username' => 'MadCocktail',
                'email' => 'randy@madcocktail.com',
                'password' => Hash::make(str_random(60)),
                'birth' => '1984-09-17',
                'verified' => 1,
            ], [
                'username' => 'Randy',
                'email' => 'Randy.Booth@gmail.com',
                'password' => Hash::make(str_random(60)),
                'birth' => '1984-09-17',
                'verified' => 1,
            ], [
                'username' => 'Abby',
                'email' => 'eabby.walsh@gmail.com',
                'password' => Hash::make(str_random(60)),
                'birth' => '1984-09-29',
                'verified' => 1,
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
