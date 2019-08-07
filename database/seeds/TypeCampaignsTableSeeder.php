<?php

use Illuminate\Database\Seeder;
use App\TypeCampaigns;

class TypeCampaignsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TypeCampaigns::create([
            'name' => 'SMS'
        ]);
    }
}
