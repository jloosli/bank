<?php

class BankTest extends TestCase {

    /**
     * @param  int                      $code
     * @param \Illuminate\Http\Response $response
     */
    public function checkJsonResponse( $code, $response ) {
        if ( $code === 200 ) {
            $this->assertTrue( $response->isOk() );
        }
        $this->assertJson( $response->getContent() );
        $this->assertEquals( $code, $response->getStatusCode() );
    }

    /**
     * @return void
     */
    public function testGetAll() {
        $this->seed();

        $response = $this->call( 'GET', '/api/banks' );

        $this->checkJsonResponse( 200, $response );
        $this->assertContains( 'banks', $response->getContent() );
        $this->assertContains( 'First Bank', $response->getContent() );
    }

    public function testCreateMissing() {
        $params   = [
            'name'     => "My New Bank",
            'password' => "Pass",
        ];
        $response = $this->call( "POST", '/api/banks', $params );
        $this->checkJsonResponse( 422, $response );
        $this->assertContains( 'compounding', $response->getContent() );
    }

    public function testCreateNew() {
        $params   = [
            'name'        => "My New Bank",
            'password'    => "Pass",
            'compounding' => 'monthly'
        ];
        $response = $this->call( "POST", '/api/banks', $params );
        $this->checkJsonResponse( 200, $response );
        $this->assertContains( '"success":true', $response->getContent() );
    }

    public function testGetBank() {
        $this->testCreateNew(); // Create a new bank so we can retrieve it
        $response = $this->call( "GET", '/api/banks/1' );
        $this->checkJsonResponse( 200, $response );
    }

    public function testUpdateBank() {
        $this->testCreateNew(); // Create a new bank so we can retrieve it
        $updates  = [ 'name' => "New Name" ];
        $response = $this->call( "PUT", '/api/banks/1', $updates );
        $this->checkJsonResponse( 200, $response );
    }

    public function testDeleteUndeleteBank() {
        $this->testCreateNew(); // Create a new bank so we can retrieve it
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

}