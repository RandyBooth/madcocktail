<?php

use Illuminate\Database\Seeder;
use App\User;

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
                'email' => 'Randy.Booth@gmail.com',
                'password' => bcrypt('11111111'),
                'role' => 1,
                'is_active' => 1
            ], [
                'email' => 'john.doe@gmail.com',
                'password' => bcrypt('11111111'),
    //            'role' => 0,
                'is_active' => 1
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
