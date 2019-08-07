<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldAttributesSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_attributes_settings', function (Blueprint $table) {
            $table->unsignedInteger('attribute_id')->nullable(false);
            $table->foreign('attribute_id')->references('id')->on('attributes_settings');

            $table->unsignedInteger('field_id')->nullable(false);
            $table->foreign('field_id')->references('id')->on('field_settings');

            $table->string('value', 255)->nullable(false);
            $table->primary(['attribute_id', 'field_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('field_attributes_settings');
    }
}
