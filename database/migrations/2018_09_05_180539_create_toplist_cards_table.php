<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateToplistCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('toplist_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('player_id')->unsigned();            
            $table->integer('red_cards')->default(0)->nullable();
            $table->integer('yellow_cards')->default(0)->nullable();
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
        Schema::dropIfExists('toplist_cards');
    }
}
