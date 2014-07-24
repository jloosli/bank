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

    public function testCreateTransactionWrongUser() {
        $notGoingToBeUsed = [
            'amount' => 35
        ];
        $response         = $this->call( 'POST', '/api/banks/1/users/400/transactions', $notGoingToBeUsed );
        $this->checkJsonResponse( 404, $response );
    }

    public function testCreateTransactionBadMissingInfo() {
        $this->seed();
        // Transaction and envelopeTransactions don't add up
        $transaction = [
            'transaction'           => [
                'amount'      => 50,
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
        $this->checkJsonResponse( 400, $response );
    }

    public function testGetIndividualTransactions() {
        $this->seed();
        $this->testCreateTransaction();
        $this->testCreateTransaction();
        $this->testCreateTransaction();

        $response = $this->call( 'GET', '/api/banks/1/users/1/transactions/1,2' );

        $this->checkJsonResponse( 200, $response );

        $this->assertContains( 'transactions', $response->getContent() );
        $this->assertContains( 'lollypops', $response->getContent() );
    }

    public function testGetMissingTransactions() {
        $response = $this->call( 'GET', '/api/banks/1/users/1/transactions/450' );
        $this->checkJsonResponse( 404, $response );
    }

}