<?php

use Illuminate\Database\Seeder;
use App\BankActionMappings;

class BankActionMappingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BankActionMappings::create([
            'bank_id'    => 1, # BMG
            'action_id'  => 1, # Saque
            'mapping_id' => 1  # Mapeamento do BMG SAQUE
        ]);
    }
}
