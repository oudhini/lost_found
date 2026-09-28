<?php

namespace Database\Seeders;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SupervisorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name'=>'Arnold',
            'email'=>'arnold]@gmail.com',
            'phone'=>'675313212',
            'role'=>'superviseur',
            'password'=>Hash::make('12345678'),
       ] );
    }
}
