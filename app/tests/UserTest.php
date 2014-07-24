<?php

class UserTest extends TestCase {

    public function testGetAll() {
        $this->seed();

        $response = $this->call( 'GET', '/api/banks' );

        $this->assertContains( 'banks', $response->getContent() );
        $this->assertContains( 'First Bank', $response->getContent() );
    }

}