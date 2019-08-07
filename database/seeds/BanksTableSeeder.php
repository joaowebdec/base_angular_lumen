<?php

use Illuminate\Database\Seeder;
use App\Banks;

class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Banks::create([
            'name' => 'BMG'
        ]);
    }
}
