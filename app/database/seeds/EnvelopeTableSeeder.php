<?php

class EnvelopeTableSeeder extends Seeder {
    public function run() {
        DB::table('envelopes')->delete();

        DB::table('envelopes')->insert(array(
            'user_id'=>1,
            'name'=>'Spending',
            'goal'=>30,
            'goal_date'=>'2015-01-01',
            'balance'=> 0,
            'percent' => 35,
            'default_spend'=>1
        ));
        DB::table('envelopes')->insert(array(
            'user_id'=>1,
            'name'=>'Savings',
            'goal'=>275,
            'goal_date'=>'2015-01-01',
            'balance'=> 0,
            'percent' => 50
        ));
        DB::table('envelopes')->insert(array(
            'user_id'=>1,
            'name'=>'Tithing',
            'goal'=>0,
            'goal_date'=>'2015-01-01',
            'balance'=> 0,
            'percent' => 10
        ));
        DB::table('envelopes')->insert(array(
            'user_id'=>2,
            'name'=>'Spending',
            'goal'=>30,
            'goal_date'=>'2015-01-01',
            'balance'=> 0,
            'percent' => 35,
            'default_spend'=>1
        ));
        DB::table('envelopes')->insert(array(
            'user_id'=>2,
            'name'=>'Savings',
            'goal'=>275,
            'goal_date'=>'2015-01-01',
            'balance'=> 0,
            'percent' => 50
        ));
    }
}

//$table->increments('id');
//$table->integer('user_id')->unsigned();
//$table->foreign('user_id')->references('id')->on('users');
//$table->string('name');
//$table->float('goal');
//$table->date('goal_date');
//$table->decimal('balance',7,2);
//$table->float('percent');
//$table->integer('default_spend')->unsigned();
//$table->timestamps();
//$table->softDeletes();
