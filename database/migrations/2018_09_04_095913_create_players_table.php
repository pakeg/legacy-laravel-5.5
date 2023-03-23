<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->string('name');
            $table->integer('country_id')->unsigned();
            $table->integer('team_id')->unsigned();
            $table->dateTime('birthdate')->nullable();
            $table->string('position');
            $table->integer('height')->unsigned();
            $table->integer('weight')->unsigned();
            $table->string('foot')->nullable();
            $table->integer('shirtnumber')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('country_id')
                ->references('id')
                ->on('countries');
        });       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');
    }
}
