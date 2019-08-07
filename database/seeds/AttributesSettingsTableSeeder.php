<?php

use Illuminate\Database\Seeder;
use App\AttributesSettings;

class AttributesSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AttributesSettings::create(['attribute' => 'required']);
        AttributesSettings::create(['attribute' => 'maxlength']);
        AttributesSettings::create(['attribute' => 'class']);
        AttributesSettings::create(['attribute' => 'min']);
        AttributesSettings::create(['attribute' => 'max']);
    }
}
