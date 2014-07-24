<?php

class UserTest extends TestCase {

    public function testGetAll() {
        $this->seed();

        $response = $this->call( 'GET', '/api/banks/1/users' );

        $this->checkJsonResponse( 200, $response );

        $this->assertContains( 'users', $response->getContent() );
        $this->assertContains( 'First User', $response->getContent() );
    }

}