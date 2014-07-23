<?php
/**
 * Created by PhpStorm.
 * User: jloosli
 * Date: 7/22/14
 * Time: 2:59 PM
 */

namespace AvantiDevelopment\JrBank;


use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract {
    public function transform(\User $user) {
        return $user;
        return  [
            'id' => (int) $user->id,
            'username' => $user->username,
            'name' => $user->name,
            'email' => $user->email,
            'slug' => $user->slug,
            'active' => (bool) $user->active,
            'bank_id' => (int) $user->bank_id,
            'user_type' => $user->user_type
        ];
    }
} 