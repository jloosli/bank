<?php

namespace AvantiDevelopment\JrBank\lib;

class CorsFilter {
    public function filter( $request, $response ) {
// Looks like either laravel or dingo api is setting allow header and not access-control-allow-methods
        if ( $request->isMethod( 'OPTIONS' ) && $response->headers->get( 'allow' ) ) {
            $response->headers->set( 'Access-Control-Allow-Methods', $response->headers->get( 'allow' ) );
        }
        if ( !$response->headers->get( 'Access-Control-Allow-Origin' ) ) {
            $response->headers->set( 'Access-Control-Allow-Origin', $request->headers->get( 'origin' ) );
        }
    }

}
