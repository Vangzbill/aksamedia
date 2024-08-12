<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'Sabil',
            'username' => 'admin',
            'email' => 'mamadhan017@gmail.com',
            'password' => bcrypt('pastibisa'),
            'phone' => '081775212021',
        ]);
    }
}
