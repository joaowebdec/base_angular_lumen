<?php

use Illuminate\Database\Seeder;
use App\Users;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Users::create([
            'name'       => 'Administrador',
            'email'      => 'admin@admin.com.br',
            'password'   => password_hash('abc@123', PASSWORD_BCRYPT),
            'image'      => null,
            'visible'    => 1,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);

        Users::create([
            'name'     => 'WebDec',
            'email'    => 'webdec@webdec.com.br',
            'password' => password_hash('abc@123wd', PASSWORD_BCRYPT),
            'image'    => null,
            'visible'  => 0
        ]);

    }
}
