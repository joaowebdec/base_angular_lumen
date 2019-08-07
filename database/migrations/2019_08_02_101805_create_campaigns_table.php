<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('sanitation_id')->nullable(false);
            $table->foreign('sanitation_id')->references('id')->on('sanitations');

            $table->unsignedInteger('type_campaign_id')->nullable(false);
            $table->foreign('type_campaign_id')->references('id')->on('campaigns');

            $table->enum('status', ['A', 'I', 'P', 'F'])->default('A'); # Aguardando processamento, Iniciada, Pausada, Finalizada

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
        Schema::dropIfExists('campaigns');
    }
}
