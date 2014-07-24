<?php

class BankTest extends TestCase {

	/**
	 * @return void
	 */
	public function testGetAll()
	{
        $this->seed();

		$crawler = $this->client->request('GET', '/api/banks');
        $response = $this->client->getResponse();

        $this->assertTrue( $response->isOk());
        $this->assertJson($response->getContent());
        $this->assertContains('banks',$response->getContent());
        $this->assertContains('First Bank',$response->getContent());
	}

    public function testCreateMissing() {
        $params = [
            'name' => "My New Bank",
            'password' => "Pass",
        ];
        $response = $this->call("POST", '/api/banks', $params);
        $this->assertEquals(422,$response->getStatusCode());
        $this->assertContains('compounding', $response->getContent());
    }

    public function testCreateNew() {
        $params = [
            'name' => "My New Bank",
            'password' => "Pass",
            'compounding' => 'monthly'
        ];
        $response = $this->call("POST", '/api/banks', $params);
        $this->assertEquals(200,$response->getStatusCode());
//        $this->assertContains('compounding', $response->getContent());
        echo $response->getContent();
    }

}