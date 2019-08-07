<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('UsersTableSeeder');
        $this->call('BanksTableSeeder');
        $this->call('ActionsTableSeeder');
        $this->call('MappingsTableSeeder');
        $this->call('BankActionMappingsTableSeeder');
        $this->call('TypeContactsSeederTable');
        $this->call('TypeCampaignsTableSeeder');
        $this->call('SettingsTableSeeder');
        $this->call('FieldSettingsTableSeeder');
        $this->call('AttributesSettingsTableSeeder');
        $this->call('FieldAttributesSettingsTableSeeder');
    }
}
