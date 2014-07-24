<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RemoveUserActive extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->dropColumn('active');
		});
		Schema::table('envelopes', function(Blueprint $table)
		{
			$table->dropColumn('is_active');
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
            $table->tinyInteger('active')->unsigned()->default(1);
		});
		Schema::table('envelopes', function(Blueprint $table)
		{
            $table->tinyInteger('is_active')->unsigned()->default(1);
		});
	}

}
