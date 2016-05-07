<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChampionMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('championMastery', function (Blueprint $table) {
		$table->bigInteger('championId');
		$table->string('championName');
		$table->bigInteger('totalMastery');
		$table->bigInteger('totalLevel');
		$table->bigInteger('totalChests');

		$table->primary('championId');
	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('championMastery');
    }
}
