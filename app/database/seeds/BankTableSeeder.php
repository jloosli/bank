<?php

class BankTableSeeder extends Seeder {
    public function run() {
        DB::table('banks')->delete();

        \AvantiDevelopment\JrBank\Bank::create(array(
            'name'=>'First Bank',
            'password'=>Hash::make('first_bank'),
            'interest'=>10,
            'compounding'=>'monthly',
            'slug'=>'first_bank',
        ));
        \AvantiDevelopment\JrBank\Bank::create(array(
            'name'=>'Second Bank',
            'password'=>Hash::make('second_bank'),
            'interest'=>10,
            'compounding'=>'monthly',
            'slug'=>'second',
        ));

    }
}