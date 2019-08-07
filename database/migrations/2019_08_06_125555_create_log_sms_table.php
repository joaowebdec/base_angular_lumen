<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_sms', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('importation_id')->nullable(false);
            $table->foreign('importation_id')->references('id')->on('importations');

            $table->integer('client_id')->nullable(false);
            $table->text('msg');
            $table->string('sms_id', 80); # Id do envio na API
            $table->string('status', 30);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_sms');
    }
}
