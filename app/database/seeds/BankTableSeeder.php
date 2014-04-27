<?php

class BankTableSeeder extends Seeder {
    public function run() {
        DB::table('banks')->delete();

        DB::table('banks')->insert(array(
            'name'=>'First Bank',
            'password'=>Hash::make('first_bank'),
            'interest'=>10,
            'compounding'=>'monthly',
            'id'=>1
        ));
    }
}