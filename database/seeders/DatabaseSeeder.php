<?php

namespace Database\Seeders;

use App\Models\Core\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         self::createFakeUsers();
         self::createDefaultAdmin();
        //  self::createDefaultMenus();
    }

    private static function createFakeUsers(){
        \App\Models\Core\User::factory(2000)->create();
    }

    private static function createDefaultAdmin(){

        User::factory()->create([
            'username' => 'admin',
            'name' => 'Administrator',
            'email' => env('APP_MAIL','admin@example.com'),
            'password' => bcrypt(env('APP_PASS','password')),
        ]);
    }
}
