<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        User::create(array(
            'email' => 'gleb@edelen.ru',
            'password' => bcrypt('12345'),
            'role' => 1
        ));

        $this->call(DictTableSeeder::class);
    }
}
