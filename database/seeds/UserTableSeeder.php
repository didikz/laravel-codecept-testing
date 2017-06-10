<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::truncate();
        factory(\App\User::class)->create([
            'email' => 'didik@gmail.com',
            'password' => bcrypt('rahasia123')
        ]);
    }
}
