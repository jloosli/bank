<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSoftDeletes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('banks', function(Blueprint $table)
		{
			$table->softDeletes();
		});
		Schema::table('users', function(Blueprint $table)
		{
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('banks', function(Blueprint $table)
        {
            $table->dropSoftDeletes();
        });
        Schema::table('users', function(Blueprint $table)
        {
            $table->dropSoftDeletes();
        });
	}

}
