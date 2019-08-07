<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSanitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sanitations', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('importation_id')->nullable(false);
            $table->foreign('importation_id')->references('id')->on('importations');

            $table->integer('total_success')->default(0);
            $table->integer('total_fail')->default(0);
            $table->enum('status', ['A', 'H', 'F'])->default('A'); # Aguardando higienizaçõ, Higienizando, Finalizado
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
        Schema::dropIfExists('sanitations');
    }
}
