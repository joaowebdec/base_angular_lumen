<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 45)->nullable(false);
            $table->string('label', 60)->nullable(false);
            $table->string('type', 15)->nullable(false);

            $table->unsignedInteger('setting_id')->nullable(false);
            $table->foreign('setting_id')->references('id')->on('settings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('field_settings');
    }
}
