<?php

class UserTest extends TestCase {

    public function testGetAll() {
        $this->seed();

        $response = $this->call( 'GET', '/api/banks/1/users' );

        $this->checkJsonResponse( 200, $response );

        $this->assertContains( 'users', $response->getContent() );
        $this->assertContains( 'First User', $response->getContent() );
    }

    public function testCreateUser() {
        $user = [
            'name' => 'Bob',
            'username' => 'bob_jones',
            'password' => 'pass',
            'email' => 'bob@bob.com',

        ];
        $response = $this->call('POST', '/api/banks/1/users', $user);
        echo $response->getContent();
        $this->checkJsonResponse(200, $response);
    }

    public function testCreateUserMissingInfo() {
        $user = [
            'name' => 'Bob',
            'username' => 'bob',
            'password' => 'pass'
        ];
        $response = $this->call('POST', '/api/banks/1/users', $user);
        $this->checkJsonResponse(422, $response);
    }

    public function testGetIndividuals() {
        $this->seed();

        $response = $this->call( 'GET', '/api/banks/1/users/1,2' );

        $this->checkJsonResponse( 200, $response );

        $this->assertContains( 'users', $response->getContent() );
        $this->assertContains( 'First User', $response->getContent() );
    }

}