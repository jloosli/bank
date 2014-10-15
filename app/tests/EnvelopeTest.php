<?php

class EnvelopeTest extends TestCase {

    public function testGetAll() {
        $this->seed();

        $response = $this->call( 'GET', '/api/banks/1/users/1/envelopes' );

        $this->checkJsonResponse( 200, $response );

        $this->assertContains( 'envelopes', $response->getContent() );
        $this->assertContains( 'user_id', $response->getContent() );
    }

    public function testCreateEnvelope() {
        $this->seed();
        $envelope = [
            'envelopes' => [
                [
                    'name'    => 'Want to spend, spend, spend',
                    'goal'    => 230,
                    'percent' => 20,
                ]
            ]
        ];
        $response = $this->call( 'POST', '/api/banks/1/users/1/envelopes', $envelope );
        $this->checkJsonResponse( 200, $response );
    }

    public function testCreateEnvelopeWrongUser() {
        $this->seed();
        $envelope = [
            'name' => 'Bob',
        ];
        $response = $this->call( 'POST', '/api/banks/10/users/1/envelopes', $envelope );
        $this->checkJsonResponse( 404, $response );
    }

    public function testGetIndividualEnvelopes() {
        $this->seed();

        $response = $this->call( 'GET', '/api/banks/1/users/1/envelopes/1,3' );

        $this->checkJsonResponse( 200, $response );

        $this->assertContains( 'Spending', $response->getContent() );
        $this->assertContains( 'Tithing', $response->getContent() );
    }

    public function testGetMissingEnvelopes() {
        $this->seed();
        $response = $this->call( 'GET', '/api/banks/1/users/1/envelopes/400' );
        $this->checkJsonResponse( 404, $response );
    }

    public function testUpdateEnvelope() {
        $this->seed();
        $updates  = [ 'name' => "Candy candy candy" ];
        $response = $this->call( "PUT", '/api/banks/1/users/1/envelopes/1', $updates );
        $this->checkJsonResponse( 200, $response );
        $this->assertContains( $updates['name'], $response->getContent() );
    }

    public function testUpdateMissingEnvelope() {
        $this->seed();
        $updates  = [ 'name' => "New Name" ];
        $response = $this->call( "PUT", '/api/banks/1/users/1/envelopes/5000', $updates );
        $this->checkJsonResponse( 404, $response );
    }

    public function testDeleteUndeleteEnvelope() {
        $this->seed(); // Create a new bank so we can retrieve it
        $response = $this->call( "DELETE", '/api/banks/1/users/1/envelopes/1' );
        $this->checkJsonResponse( 200, $response );

        $response = $this->call( "GET", '/api/banks/1/users/1/envelopes/1' );
        $this->checkJsonResponse( 404, $response );

        $response = $this->call( "GET", '/api/banks/1/users/1/envelopes/1?show_deleted=true' );
        $this->checkJsonResponse( 200, $response );

        $response = $this->call( "PUT", '/api/banks/1/users/1/envelopes/1?undelete=true' );
        $this->checkJsonResponse( 200, $response );

        $response = $this->call( "GET", '/api/banks/1/users/1/envelopes/1' );
        $this->checkJsonResponse( 200, $response );

    }

    public function testDeleteMissingEnvelope() {
        $response = $this->call( "DELETE", '/api/banks/1/users/1/envelopes/66005' );
        $this->checkJsonResponse( 404, $response );
    }


}