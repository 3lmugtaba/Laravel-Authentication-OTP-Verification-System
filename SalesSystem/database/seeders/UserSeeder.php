<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'name' => 'mugtaba',
            'email' => 'mugtaba@gmail.com',
            'phone' => '0125313532',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'telegram_chat_id' => '7651234625',
            'is_Verified' => true ,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        User::factory(14)->create();
    }
}
