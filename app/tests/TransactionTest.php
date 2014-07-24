<?php

class TransactionTest extends TestCase {

    public function testGetAll() {
        $this->seed();

        $response = $this->call( 'GET', '/api/banks/1/users/1/transactions' );

        $this->checkJsonResponse( 200, $response );

        $this->assertContains( 'current_page', $response->getContent() );
        $this->assertContains( 'data', $response->getContent() );
    }

    public function testCreateTransaction() {
        $this->seed();
        $transaction = [
            'transaction'           => [
                'amount'      => 21.57,
                'description' => "Wanted to get some lollypops",
            ],
            'envelope_transactions' => [
                [
                    'envelope_id' => 1,
                    'amount'      => 10,
                ],
                [
                    'envelope_id' => 2,
                    'amount'      => 21.57 - 35,
                ],
                [
                    'envelope_id' => 3,
                    'amount'      => 25,
                ]
            ]
        ];
        $response    = $this->call( 'POST', '/api/banks/1/users/1/transactions', $transaction );
        $this->checkJsonResponse( 200, $response );
    }

    public function testCreateUserMissingInfo() {
        $user     = [
            'name'     => 'Bob',
            'username' => 'bob',
            'password' => 'pass'
        ];
        $response = $this->call( 'POST', '/api/banks/1/users', $user );
        $this->checkJsonResponse( 422, $response );
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

}