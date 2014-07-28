<?php

class EnvelopeTableSeeder extends Seeder {
    public function run() {
        DB::table('envelopes')->delete();

        \AvantiDevelopment\JrBank\Envelope::create(array(
            'user_id'=>1,
            'name'=>'Spending',
            'goal'=>30,
            'goal_date'=>'2015-01-01',
            'balance'=> 0,
            'percent' => 35,
            'default_spend'=>1
        ));
        \AvantiDevelopment\JrBank\Envelope::create(array(
            'user_id'=>1,
            'name'=>'Savings',
            'goal'=>275,
            'goal_date'=>'2015-01-01',
            'balance'=> 0,
            'percent' => 50
        ));
        \AvantiDevelopment\JrBank\Envelope::create(array(
            'user_id'=>1,
            'name'=>'Tithing',
            'goal'=>0,
            'goal_date'=>'2015-01-01',
            'balance'=> 0,
            'percent' => 10
        ));
        \AvantiDevelopment\JrBank\Envelope::create(array(
            'user_id'=>2,
            'name'=>'Long Term',
            'goal'=>30,
            'goal_date'=>'2015-01-01',
            'balance'=> 0,
            'percent' => 35,
            'default_spend'=>1
        ));
        \AvantiDevelopment\JrBank\Envelope::create(array(
            'user_id'=>2,
            'name'=>'Savings',
            'goal'=>275,
            'goal_date'=>'2015-01-01',
            'balance'=> 0,
            'percent' => 50
        ));
    }
}

