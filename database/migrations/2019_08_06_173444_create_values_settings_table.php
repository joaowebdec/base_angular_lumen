<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValuesSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('values_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value', 255)->nullable(false);

            $table->unsignedInteger('field_id')->nullable(false);
            $table->foreign('field_id')->references('id')->on('field_settings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('values_settings');
    }
}
