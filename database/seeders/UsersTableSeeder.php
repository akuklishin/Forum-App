<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'role' => 'user',
                'userName' => 'jSmith',
                'email' => 'smith@gmail.com',
                'password' => 'qwerty',
                'rating' => 0
            ],
            [
                'role' => 'user',
                'userName' => 'LaraCroft',
                'email' => 'lara@gmail.com',
                'password' => '123456',
                'rating' => 0
            ],
        ];

        foreach ($users as $key => $value)
            User::create($value);
    }
}
