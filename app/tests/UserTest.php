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

    public function testGetMissingIndividuals() {
        $response = $this->call( 'GET', '/api/banks/1/users/800' );
        $this->checkJsonResponse( 404, $response );
    }

    public function testUpdateUser() {
        $this->seed();
        $updates  = [ 'name' => "bob" ];
        $response = $this->call( "PUT", '/api/banks/1/users/1', $updates );
        $this->checkJsonResponse( 200, $response );
    }

    public function testUpdateMissingUser() {
        $this->seed();
        $updates  = [ 'name' => "New Name" ];
        $response = $this->call( "PUT", '/api/banks/1/users/400', $updates );
        $this->checkJsonResponse( 404, $response );
    }

    public function testDeleteUndeleteUser() {
        $this->seed(); // Create a new bank so we can retrieve it
        $response = $this->call( "DELETE", '/api/banks/1/users/1' );
        $this->checkJsonResponse( 200, $response );

        $response = $this->call( "GET", '/api/banks/1/users/1' );
        $this->checkJsonResponse( 404, $response );

        $response = $this->call( "GET", '/api/banks/1/users/1?show_deleted=true' );
        $this->checkJsonResponse( 200, $response );

        $response = $this->call( "PUT", '/api/banks/1/users/1?undelete=true' );
        $this->checkJsonResponse( 200, $response );

        $response = $this->call( "GET", '/api/banks/1/users/1' );
        $this->checkJsonResponse( 200, $response );

    }

    public function testDeleteMissingBank() {
        $response = $this->call( "DELETE", '/api/banks/200' );
        $this->checkJsonResponse( 404, $response );
    }




}