<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnvelopesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('envelopes', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name');
            $table->float('goal')->nullable();
            $table->date('goal_date')->nullable();
            $table->decimal('balance',7,2);
            $table->integer('percent')->default(0);
            $table->tinyInteger('default_spend')->unsigned()->default(0);
            $table->tinyInteger('is_active')->unsigned()->default(1);
			$table->timestamps();
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
		Schema::drop('envelopes');
	}

}
