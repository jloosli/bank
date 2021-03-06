<?php

class BankTest extends TestCase {

     public function testGetAllRegularUser() {
        $this->seed();

         $user = \AvantiDevelopment\JrBank\Models\User::find(1);
         API::be($user);

        $response = $this->call( 'GET', '/api/banks' );

        $this->checkJsonResponse( 200, $response );
        $this->assertContains( 'banks', $response->getContent() );
        $this->assertContains( 'First Bank', $response->getContent() );
         $this->assertNotContains('Second Bank', $response->getContent());
    }
     public function testGetAllSuperUser() {
        $this->seed();

         $user = \AvantiDevelopment\JrBank\Models\User::find(2);
         API::be($user);

        $response = $this->call( 'GET', '/api/banks' );

        $this->checkJsonResponse( 200, $response );
        $this->assertContains( 'banks', $response->getContent() );
        $this->assertContains( 'First Bank', $response->getContent() );
         $this->assertContains('Second Bank', $response->getContent());
    }

    public function testCreateMissingParameter() {
        $params   = [
            'name' => "My New Bank",
            'password' => "Pass",
        ];
        $response = $this->call( "POST", '/api/banks', $params );
        $this->checkJsonResponse( 422, $response );
        $this->assertContains( 'bank', $response->getContent() );
    }

    public function testCreateNew() {
        $params   = [
            'bank' => [
            'name'        => "My New Bank",
            'password'    => "Pass",
            'compounding' => 'monthly',
            'interest'    => 10
            ]
        ];
        $response = $this->call( "POST", '/api/banks', $params );
        $this->checkJsonResponse( 200, $response );
        $this->assertContains( '"success":true', $response->getContent() );
    }

    public function testGetBank() {
        $this->seed();
        $response = $this->call( "GET", '/api/banks/1' );
        $this->checkJsonResponse( 200, $response );
    }

    public function testGetMissingBank() {
        $this->seed();
        $response = $this->call( "GET", '/api/banks/25' );
        $this->checkJsonResponse( 404, $response );
    }

    public function testUpdateBank() {
        $this->seed();
        $updates  = [ 'name' => "New Name" ];
        $response = $this->call( "PUT", '/api/banks/1', $updates );
        $this->checkJsonResponse( 200, $response );
    }

    public function testUpdateMissingBank() {
        $this->seed();
        $updates  = [ 'name' => "New Name" ];
        $response = $this->call( "PUT", '/api/banks/20', $updates );
        $this->checkJsonResponse( 404, $response );
    }

    public function testDeleteUndeleteBank() {
        $this->seed(); // Create a new bank so we can retrieve it
        $response = $this->call( "DELETE", '/api/banks/1' );
        $this->checkJsonResponse( 200, $response );

        $response = $this->call( "GET", '/api/banks/1' );
        $this->checkJsonResponse( 404, $response );

        $response = $this->call( "GET", '/api/banks/1?show_deleted=true' );
        $this->checkJsonResponse( 200, $response );

        $response = $this->call( "PUT", '/api/banks/1?undelete=true' );
        $this->checkJsonResponse( 200, $response );

        $response = $this->call( "GET", '/api/banks/1' );
        $this->checkJsonResponse( 200, $response );

    }

    public function testDeleteMissingBank() {
        $response = $this->call( "DELETE", '/api/banks/200' );
        $this->checkJsonResponse( 404, $response );
    }


}