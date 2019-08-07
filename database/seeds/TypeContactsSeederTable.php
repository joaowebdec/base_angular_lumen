<?php

use Illuminate\Database\Seeder;
use App\TypeContacts;

class TypeContactsSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TypeContacts::create([
            'description' => 'Celular'
        ]);

        TypeContacts::create([
            'description' => 'Email'
        ]);
    }
}
