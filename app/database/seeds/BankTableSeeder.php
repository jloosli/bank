<?php

use AvantiDevelopment\JrBank\Models\Bank;

class BankTableSeeder extends Seeder {
    public function run() {
        DB::table('banks')->delete();

        Bank::create(array(
            'name'=>'First Bank',
            'password'=>Hash::make('first_bank'),
            'interest'=>10,
            'compounding'=>'monthly',
            'slug'=>'first_bank',
        ));
        Bank::create(array(
            'name'=>'Second Bank',
            'password'=>Hash::make('second_bank'),
            'interest'=>10,
            'compounding'=>'monthly',
            'slug'=>'second',
        ));

    }
}