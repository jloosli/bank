<?php
/**
 * Created by PhpStorm.
 * User: jloosli
 * Date: 7/28/14
 * Time: 4:02 PM
 */

namespace AvantiDevelopment\JrBank\Auth;


use Dingo\Api\Auth\Provider;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class BasicProvider extends Provider {

    /**
     * Authenticate the request and return the authenticated user instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Routing\Route $route
     *
     * @return mixed
     */
    public function authenticate( Request $request, Route $route ) {
        $authorization = $request->header( 'Authorization' );
        if ( !$authorization ) {
            return false;
        }
        $authparts     = explode( ' ', $authorization );
        $auth64        = $authparts[1];
        $decoded       = base64_decode( $auth64 );
        $decoded_parts = explode( ':', $decoded );
        $token         = $decoded_parts[0];

        if ( $token ) {
            $auth = \AvantiDevelopment\JrBank\Oauth::where( 'token', $token )->first();
            if ( !$auth ) {
                return false;
            }
            $user = $auth->user();

            return $user;
        }

        return false;

    }
}