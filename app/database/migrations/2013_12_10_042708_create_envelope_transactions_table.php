<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnvelopeTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('envelope_transactions', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('transaction_id')->unsigned();
            $table->integer('envelope_id')->unsigned();
            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->foreign('envelope_id')->references('id')->on('envelopes');
            $table->decimal('amount',7,2);
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
		Schema::drop('envelope_transactions');
	}

}
