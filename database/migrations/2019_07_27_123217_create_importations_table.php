<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('importations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description', 50)->nullable(false);
            $table->string('file_name', 30)->nullable(false);

            $table->unsignedInteger('user_id')->nullable(false);
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedInteger('bank_id')->nullable(false);
            $table->foreign('bank_id')->references('id')->on('banks');

            $table->unsignedInteger('action_id')->nullable(false);
            $table->foreign('action_id')->references('id')->on('actions');

            $table->integer('total_success')->default(0);
            $table->integer('total_fail')->default(0);
            $table->enum('status', ['A', 'E', 'S', 'P'])->default('A');
            $table->text('mapping')->nullable(false);
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
        Schema::dropIfExists('importations');
    }
}
