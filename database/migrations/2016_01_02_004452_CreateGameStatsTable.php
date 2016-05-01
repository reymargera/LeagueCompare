<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gamestats', function (Blueprint $table) {
            $table->bigInteger('summonerId');
            $table->bigInteger('matchId');
            $table->string('region', 4);
            $table->bigInteger('matchDuration');
            $table->string('season', 15);
            $table->string('tier', 10);
            $table->string('division', 5);
            $table->string('lane', 6);
            $table->string('role', 15);
            $table->integer('championId');
            $table->string('champion');
            $table->bigInteger('kills');
            $table->bigInteger('deaths');
            $table->bigInteger('assists');
            $table->bigInteger('creepScore');
            $table->bigInteger('goldEarned');
            $table->bigInteger('doubles');
            $table->bigInteger('triples');
            $table->bigInteger('quadras');
            $table->bigInteger('pentas');
            $table->bigInteger('wardsBought');
            $table->bigInteger('wardsPlaced');
            $table->bigInteger('wardsKilled');
            $table->bigInteger('damageDealt');
            $table->bigInteger('damageTaken');
            $table->bigInteger('crowdControlTimeDealt');

            $table->timestamps();

            $table->primary(['summonerId','matchId']);
        });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('gamestats');
    }
}
