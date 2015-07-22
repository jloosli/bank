<?php
/**
 * Created by PhpStorm.
 * User: jloosli
 * Date: 7/28/14
 * Time: 4:02 PM
 */

namespace AvantiDevelopment\JrBank\Auth;


use AvantiDevelopment\JrBank\Models\User;
use Dingo\Api\Auth\ProviderInterface;
use Dingo\Api\Routing\Route;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use UnexpectedValueException;

//use Illuminate\Routing\Route;

class BasicProvider implements ProviderInterface {

    /**
     * Authenticate the request and return the authenticated user instance.
     *
     * @param  \Illuminate\Http\Request       $request
     * @param Route|\Illuminate\Routing\Route $route
     *
     * @return mixed
     */
    public function authenticate( Request $request, Route $route = null ) {
        $authorization = $request->header( 'Authorization' );
        if ( !$authorization ) {
            throw new UnauthorizedHttpException( null, 'Missing authorization header.' );
        }
        $authparts = explode( ' ', $authorization );
        $auth      = $authparts[1];
        try {
            $payload = \JWT::decode( $auth, $_ENV['token'] );
        } catch ( UnexpectedValueException $e ) {
            throw new UnauthorizedHttpException( null, 'Token not valid.' );
        } catch ( \DomainException $e) {
            throw new UnauthorizedHttpException(null, 'Token not valid.');
        }

        $user = User::find( $payload->user->id );

        if ( !$user ) {
            throw new UnauthorizedHttpException( null, 'Could not authenticate.' );
        } else {
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