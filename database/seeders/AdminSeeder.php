<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       User::create([
        'name'=>'Hussam',
        'email'=>'hussam@gmail.com',
        'password'=>Hash::make('11111111'),
        'isAdmin'=>true
       ]);
    }
}
