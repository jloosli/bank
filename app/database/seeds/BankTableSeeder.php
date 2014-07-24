<?php

class BankTableSeeder extends Seeder {
    public function run() {
        DB::table('banks')->delete();

        Bank::create(array(
            'name'=>'First Bank',
            'password'=>Hash::make('first_bank'),
            'interest'=>10,
            'compounding'=>'monthly',
//            'id'=>1,
            'slug'=>'first_bank',
//            'created_at' => '0000-00-00 00:00:00',
//            'updated_at' => '0000-00-00 00:00:00'

        ));

    }
}