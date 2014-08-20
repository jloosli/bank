<?php

use AvantiDevelopment\JrBank\Models\Envelope;

class EnvelopeTableSeeder extends Seeder {
    public function run() {
        DB::table('envelopes')->delete();

        Envelope::create(array(
            'user_id'=>1,
            'name'=>'Spending',
            'goal'=>30,
            'goal_date'=>'2015-01-01',
            'balance'=> 0,
            'percent' => 35,
            'default_spend'=>1
        ));
        Envelope::create(array(
            'user_id'=>1,
            'name'=>'Savings',
            'goal'=>275,
            'goal_date'=>'2015-01-01',
            'balance'=> 0,
            'percent' => 50
        ));
        Envelope::create(array(
            'user_id'=>1,
            'name'=>'Tithing',
            'goal'=>0,
            'goal_date'=>'2015-01-01',
            'balance'=> 0,
            'percent' => 10
        ));
        Envelope::create(array(
            'user_id'=>2,
            'name'=>'Long Term',
            'goal'=>30,
            'goal_date'=>'2015-01-01',
            'balance'=> 0,
            'percent' => 35,
            'default_spend'=>1
        ));
        Envelope::create(array(
            'user_id'=>2,
            'name'=>'Savings',
            'goal'=>275,
            'goal_date'=>'2015-01-01',
            'balance'=> 0,
            'percent' => 50
        ));
    }
}

