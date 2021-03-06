<?php

use \AvantiDevelopment\JrBank\Models\User;

class UserTableSeeder extends Seeder {
    public function run() {
        DB::table('users')->delete();

        User::create(array(
            'username'=>'first_user',
            'name'=>'First User',
            'email'=>'first@example.com',
            'password'=>Hash::make('first_user'),
            'bank_id'=>1,
            'user_type'=>'user',
            'balance'=>0
        ));
        User::create(array(
            'username'=>'second_user',
            'name'=>'Second User',
            'email'=>'second@example.com',
            'password'=>Hash::make('second_user'),
            'bank_id'=>1,
            'user_type'=>'super-admin',
            'balance'=>0
        ));
    }
}