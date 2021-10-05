<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon;

class usersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'login' => "Admin",
            'real_name' => Str::random(10),
            'email' => "anton271993@ukr.net",
            'password' => Hash::make('2222'),
            'rating' => -3,
            'role' => "admin",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('users')->insert([
            'login' => "User1",
            'real_name' => Str::random(10),
            'email' => "anton3@ukr.net",
            'password' => Hash::make('2222'),
            'rating' => 2,
            'role' => "user",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('users')->insert([
            'login' => "User2",
            'real_name' => Str::random(10),
            'email' => "f@ukr.net",
            'password' => Hash::make('2222'),
            'role' => "user",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('users')->insert([
            'login' => "User3",
            'real_name' => Str::random(10),
            'email' => "g@ukr.net",
            'password' => Hash::make('2222'),
            'role' => "user",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('users')->insert([
            'login' => "User4",
            'real_name' => Str::random(10),
            'email' => "h@ukr.net",
            'password' => Hash::make('2222'),
            'role' => "user",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
