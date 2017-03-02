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
                'password' => Hash::make('@Please_Change_This_Password_4321!'),
                'role' => 1,
                'verified' => 1,
            ], [
                'username' => 'MadCocktail',
                'email' => 'randy@madcocktail.com',
                'password' => Hash::make('@Please_Change_This_Password_4321!'),
                'verified' => 1,
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
