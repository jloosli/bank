<?php
namespace AvantiDevelopment\JrBank;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class OauthController extends \BaseController {

    /**
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function loginWithGoogle() {
        // get data from input
        $code = Input::get( 'code' );

        // get google service
        $googleService = \Artdarek\OAuth\Facade\OAuth::consumer( 'Google' );

        // check if code is valid

        // if code is provided get user data and sign in
        if ( !empty( $code ) ) {

            // This was a callback request from google, get the token
            $token = $googleService->requestAccessToken( $code );

            // Send a request with it
            $result = json_decode( $googleService->request( 'https://www.googleapis.com/oauth2/v1/userinfo' ), true );
//            $result = json_decode( $googleService->request( 'https://www.googleapis.com/auth/plus.login' ), true );

            $message = 'Your unique Google user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
            echo $message . "<br/>";
//            printf( "Your unique token is: %s<br/>", Oauth::storeCredentials( 'google', $result ) );

            //Var_dump
            //display whole array().
            dd( $result );

        } // if not ask for permission first
        else {
            // get googleService authorization
            $url = $googleService->getAuthorizationUri();
            echo $url;

            // return to google login url
//            return Redirect::to( (string) $url );
        }
    }

}