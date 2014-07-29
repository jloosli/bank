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
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class BasicProvider extends Provider {

    /**
     * Authenticate the request and return the authenticated user instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Routing\Route $route
     *
     * @throws UnauthorizedHttpException
     * @return mixed
     */
    public function authenticate( Request $request, Route $route ) {
        $authorization = $request->header( 'Authorization' );
        if ( !$authorization ) {
            throw new UnauthorizedHttpException(null, 'Could not authenticate.');
        }
        $authparts     = explode( ' ', $authorization );
        $auth64        = $authparts[1];
        $decoded       = base64_decode( $auth64 );
        $decoded_parts = explode( ':', $decoded );
        $token         = $decoded_parts[0];

        if ( $token ) {
            $auth = \AvantiDevelopment\JrBank\Oauth::where( 'token', $token )->first();
            if ( $auth ) {
                return  $auth->user;;
            }
        }

        throw new UnauthorizedHttpException(null, 'Could not authenticate.');

    }
}