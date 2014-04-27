<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('username');
            $table->string('password');
            $table->string('token');
            $table->string('name');
            $table->string('email');
            $table->string('slug');
            $table->tinyInteger('active')->unsigned()->default(1);
            $table->integer('bank_id')->unsigned();
            $table->foreign('bank_id')->references('id')->on('banks');
            $table->enum('user_type', array('user','admin','super-admin'));
            $table->decimal('balance',7,2);
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
//        Schema::table('users')->dropForeign('bank_id');
		Schema::drop('users');
	}

}
