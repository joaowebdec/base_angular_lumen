<?php

use Illuminate\Database\Seeder;
use App\Actions;

class ActionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Actions::create([
            'name'    => 'Cadastro (Saque)'
        ]);
    }
}
