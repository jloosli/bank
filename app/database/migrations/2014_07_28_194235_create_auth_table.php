<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAuthTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('oauths', function(Blueprint $table)
		{
            $table->increments('id');
			$table->integer('user_id');
			$table->enum('oauth_provider',['none','twitter','facebook','google']);
            $table->string('oauth_uid');
            $table->string('token')->unique();
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
		Schema::drop('oauths');
	}

}
