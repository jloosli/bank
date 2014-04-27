<?php
/*
Place events here
*/

Event::listen('auth.token.valid', function($user)
{
    //Token is valid, set the user on auth system.
    Auth::setUser($user);
});

