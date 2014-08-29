<?php

class HomeController extends BaseController {

    public function index()
    {
        $public = public_path();
        $idx = File::get( $public . '/dev/index.html' );
        return Redirect::to('/dev/index.html');
    }

}