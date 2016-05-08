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
		$table->bigInteger('totalMastery')->default(0);
		$table->bigInteger('totalLevel')->default(0);
		$table->bigInteger('totalChests')->default(0);
		$table->bigInteger('count')->default(0);

		$table->primary('championId');
	});

	//Initializing Values
	DB::table('championMastery')->insert(
		array(
		array('championId' => 412, 'championName' => 'Thresh'),
		array('championId' => 266, 'championName' => 'Aatrox'),
		array('championId' => 23, 'championName' => 'Tryndamere'),
array('championId' => 79, 'championName' => 'Gragas'),
array('championId' => 69, 'championName' => 'Cassiopeia'),
array('championId' => 136, 'championName' => 'Aurelion Sol'),
array('championId' => 13, 'championName' => 'Ryze'),
array('championId' => 78, 'championName' => 'Poppy'),
array('championId' => 14, 'championName' => 'Sion'),
array('championId' => 202, 'championName' => 'Jhin'),
array('championId' => 1, 'championName' => 'Annie'),
array('championId' => 111, 'championName' => 'Nautilus'),
array('championId' => 43, 'championName' => 'Karma'),
array('championId' => 99, 'championName' => 'Lux'),
array('championId' => 103, 'championName' => 'Ahri'),
array('championId' => 2, 'championName' => 'Olaf'),
array('championId' => 112, 'championName' => 'Viktor'),
array('championId' => 27, 'championName' => 'Singed'),
array('championId' => 86, 'championName' => 'Garen'),
array('championId' => 34, 'championName' => 'Anivia'),
array('championId' => 57, 'championName' => 'Maokai'),
array('championId' => 127, 'championName' => 'Lissandra'),
array('championId' => 25, 'championName' => 'Morgana'),
array('championId' => 105, 'championName' => 'Fizz'),
array('championId' => 28, 'championName' => 'Evelynn'),
array('championId' => 238, 'championName' => 'Zed'),
array('championId' => 74, 'championName' => 'Heimerdinger'),
array('championId' => 68, 'championName' => 'Rumble'),
array('championId' => 37, 'championName' => 'Sona'),
array('championId' => 82, 'championName' => 'Mordekaiser'),
array('championId' => 96, 'championName' => "Kog'Maw"),
array('championId' => 55, 'championName' => 'Katarina'),
array('championId' => 117, 'championName' => 'Lulu'),
array('championId' => 22, 'championName' => 'Ashe'),
array('championId' => 30, 'championName' => 'Karthus'),
array('championId' => 12, 'championName' => 'Alistar'),
array('championId' => 122, 'championName' => 'Darius'),
array('championId' => 67, 'championName' => 'Vayne'),
array('championId' => 110, 'championName' => 'Varus'),
array('championId' => 77, 'championName' => 'Udyr'),
array('championId' => 89, 'championName' => 'Leona'),
array('championId' => 126, 'championName' => 'Jayce'),
array('championId' => 134, 'championName' => 'Syndra'),
array('championId' => 80, 'championName' => 'Pantheon'),
array('championId' => 92, 'championName' => 'Riven'),
array('championId' => 121, 'championName' => "Kha'Zix"),
array('championId' => 42, 'championName' => 'Corki'),
array('championId' => 51, 'championName' => 'Caitlyn'),
array('championId' => 268, 'championName' => 'Azir'),
array('championId' => 76, 'championName' => 'Nidalee'),
array('championId' => 85, 'championName' => 'Kennen'),
array('championId' => 3, 'championName' => 'Galio'),
array('championId' => 45, 'championName' => 'Veigar'),
array('championId' => 432, 'championName' => 'Bard'),
array('championId' => 150, 'championName' => 'Gnar'),
array('championId' => 90, 'championName' => 'Malzahar'),
array('championId' => 104, 'championName' => 'Graves'),
array('championId' => 254, 'championName' => 'Vi'),
array('championId' => 10, 'championName' => 'Kayle'),
array('championId' => 39, 'championName' => 'Irelia'),
array('championId' => 64, 'championName' => 'Lee Sin'),
array('championId' => 420, 'championName' => 'Illaoi'),
array('championId' => 60, 'championName' => 'Elise'),
array('championId' => 106, 'championName' => 'Volibear'),
array('championId' => 20, 'championName' => 'Nunu'),
array('championId' => 4, 'championName' => 'Twisted Fate'),
array('championId' => 24, 'championName' => 'Jax'),
array('championId' => 102, 'championName' => 'Shyvana'),
array('championId' => 429, 'championName' => 'Kalista'),
array('championId' => 36, 'championName' => 'Dr, Mundo'),
array('championId' => 223, 'championName' => 'Tahm Kench'),
array('championId' => 131, 'championName' => 'Diana'),
array('championId' => 63, 'championName' => 'Brand'),
array('championId' => 113, 'championName' => 'Sejuani'),
array('championId' => 8, 'championName' => 'Vladimir'),
array('championId' => 154, 'championName' => 'Zac'),
array('championId' => 421, 'championName' => "Rek'Sai"),
array('championId' => 133, 'championName' => 'Quinn'),
array('championId' => 84, 'championName' => 'Akali'),
array('championId' => 18, 'championName' => 'Tristana'),
array('championId' => 120, 'championName' => 'Hecarim'),
array('championId' => 15, 'championName' => 'Sivir'),
array('championId' => 236, 'championName' => 'Lucian'),
array('championId' => 107, 'championName' => 'Rengar'),
array('championId' => 19, 'championName' => 'Warwick'),
array('championId' => 72, 'championName' => 'Skarner'),
array('championId' => 54, 'championName' => 'Malphite'),
array('championId' => 157, 'championName' => 'Yasuo'),
array('championId' => 101, 'championName' => 'Xerath'),
array('championId' => 17, 'championName' => 'Teemo'),
array('championId' => 58, 'championName' => 'Renekton'),
array('championId' => 75, 'championName' => 'Nasus'),
array('championId' => 119, 'championName' => 'Draven'),
array('championId' => 35, 'championName' => 'Shaco'),
array('championId' => 50, 'championName' => 'Swain'),
array('championId' => 115, 'championName' => 'Ziggs'),
array('championId' => 91, 'championName' => 'Talon'),
array('championId' => 40, 'championName' => 'Janna'),
array('championId' => 245, 'championName' => 'Ekko'),
array('championId' => 61, 'championName' => 'Orianna'),
array('championId' => 114, 'championName' => 'Fiora'),
array('championId' => 9, 'championName' => 'Fiddlesticks'),
array('championId' => 33, 'championName' => 'Rammus'),
array('championId' => 31, 'championName' => "Cho'Gath"),
array('championId' => 7, 'championName' => 'LeBlanc'),
array('championId' => 26, 'championName' => 'Zilean'),
array('championId' => 16, 'championName' => 'Soraka'),
array('championId' => 56, 'championName' => 'Nocturne'),
array('championId' => 222, 'championName' => 'Jinx'),
array('championId' => 83, 'championName' => 'Yorick'),
array('championId' => 6, 'championName' => 'Urgot'),
array('championId' => 203, 'championName' => 'Kindred'),
array('championId' => 21, 'championName' => 'Miss Fortune'),
array('championId' => 62, 'championName' => 'Wukong'),
array('championId' => 53, 'championName' => 'Blitzcrank'),
array('championId' => 98, 'championName' => 'Shen'),
array('championId' => 201, 'championName' => 'Braum'),
array('championId' => 5, 'championName' => 'Xin Zhao'),
array('championId' => 29, 'championName' => 'Twitch'),
array('championId' => 11, 'championName' => 'Master Yi'),
array('championId' => 44, 'championName' => 'Taric'),
array('championId' => 32, 'championName' => 'Amumu'),
array('championId' => 41, 'championName' => 'Gangplank'),
array('championId' => 48, 'championName' => 'Trundle'),
array('championId' => 38, 'championName' => 'Kassadin'),
array('championId' => 161, 'championName' => "Vel'Koz"),
array('championId' => 143, 'championName' => 'Zyra'),
array('championId' => 267, 'championName' => 'Nami'),
array('championId' => 59, 'championName' => 'Jarvan IV'),
array('championId' => 81, 'championName' => 'Ezreal'))
	);
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
