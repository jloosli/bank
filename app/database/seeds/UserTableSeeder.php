<?php

class UserTableSeeder extends Seeder {
    public function run() {
        DB::table('users')->delete();

        \AvantiDevelopment\JrBank\User::create(array(
            'username'=>'first_user',
            'name'=>'First User',
            'email'=>'first@example.com',
            'password'=>Hash::make('first_user'),
            'bank_id'=>1,
            'slug'=>'first_user',
            'token'=>'',
            'user_type'=>'user',
            'balance'=>0
        ));
        \AvantiDevelopment\JrBank\User::create(array(
            'username'=>'second_user',
            'name'=>'Second User',
            'email'=>'second@example.com',
            'password'=>Hash::make('second_user'),
            'bank_id'=>1,
            'token'=>'',
            'slug'=>'second_user',
            'user_type'=>'super_admin',
            'balance'=>0
        ));
    }
}