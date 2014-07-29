<?php

class OauthTest extends TestCase {

    public function setUp() {
        parent::setUp();
        $this->resetEvents();
    }

    /*
     * Hack to get the events to a testable state
     * @references https://github.com/laravel/framework/issues/1181
     */
    private function resetEvents() {
        AvantiDevelopment\JrBank\Oauth::flushEventListeners();
        AvantiDevelopment\JrBank\Oauth::boot();
    }

    /*
     * @expectedException \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function testStoreBadCredentials() {

        $oauth_model = new AvantiDevelopment\JrBank\Oauth();
        try {
            $oauth_model->storeCredentials( 'google', [ 'email' => 'bob@bob.com' ] );
            $this->assertTrue(false);
        } catch (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e) {
            $this->assertTrue(true);
        }
    }

    public function testStoreCredentials() {
        $this->seed();

        $credentials = ['id'=> '23425523523', 'email' => 'first@example.com'];
        $oauth_model = new AvantiDevelopment\JrBank\Oauth();
        $token = $oauth_model->storeCredentials('google',$credentials);
        $this->assertTrue(is_string($token));
    }
}