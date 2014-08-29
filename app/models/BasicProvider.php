<?php
/**
 * Created by PhpStorm.
 * User: jloosli
 * Date: 7/28/14
 * Time: 4:02 PM
 */

namespace AvantiDevelopment\JrBank\Auth;


use AvantiDevelopment\JrBank\Models\User;
use AvantiDevelopment\JrBank\Oauth;
use Dingo\Api\Auth\Provider;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use UnexpectedValueException;

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
    public function authenticate( Request $request, Route $route = null ) {
        $authorization = $request->header( 'Authorization' );
        if ( !$authorization ) {
            throw new UnauthorizedHttpException( null, 'Missing authorization header.' );
        }
        $authparts     = explode( ' ', $authorization );
        $auth        = $authparts[1];
        try {
            $payload = \JWT::decode( $auth, $_ENV['token'] );
        } catch (UnexpectedValueException $e) {
            throw new UnauthorizedHttpException(null,'Token not valid.' );
        }

        $user = User::find($payload->user->id);

        if(!$user) {
            throw new UnauthorizedHttpException( null, 'Could not authenticate.' );
        }else{
            // Check to make sure user has access to the bank
            if ( $route->parameter( 'bank_id' ) ) {
                if ( $user->user_type !== 'super-admin' && $user->bank_id !== (int) $route->parameter( 'bank_id' ) ) {
                    throw new UnauthorizedHttpException( null, 'Unable to access this bank.' );
                }
            }
            return $user;
        }



    }
}