<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSanitationLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sanitation_lots', function (Blueprint $table) {

            $table->unsignedInteger('importation_id')->nullable(false);
            $table->foreign('importation_id')->references('id')->on('importations');

            $table->integer('client_id')->nullable(false);
            $table->enum('status', ['S', 'E'])->default('S'); # Sucesso, Erro
            $table->text('obs');

            $table->primary(['importation_id', 'client_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sanitation_lots');
    }
}
