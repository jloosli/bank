<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTokens extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
            $table->string('facebook')->nullable();
            $table->string('foursquare')->nullable();
            $table->string('github')->nullable();
            $table->string('google')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('twitter')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
            $table->dropColumn('facebook');
            $table->dropColumn('foursquare');
            $table->dropColumn('github');
            $table->dropColumn('google');
            $table->dropColumn('linkedin');
            $table->dropColumn('twitter');

        });
	}

}
