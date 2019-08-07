<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankActionMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_action_mappings', function (Blueprint $table) {
            $table->unsignedInteger('bank_id')->nullable(false);
            $table->foreign('bank_id')->references('id')->on('banks');

            $table->unsignedInteger('action_id')->nullable(false);
            $table->foreign('action_id')->references('id')->on('actions');

            $table->unsignedInteger('mapping_id')->nullable(false);
            $table->foreign('mapping_id')->references('id')->on('mappings');

            $table->primary(['bank_id', 'action_id', 'mapping_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_action_mappings');
    }
}
