<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {
    /**
     * @param  int                      $code
     * @param \Illuminate\Http\Response $response
     */
    public function checkJsonResponse( $code, $response ) {
        $this->assertJson( $response->getContent() );
        $this->assertEquals( $code, $response->getStatusCode() );
        if ( $code === 200 ) {
            $this->assertTrue( $response->isOk() );
        }
    }




    /**
     * Default preparation for each test
     */
    public function setUp() {
        parent::setUp();

        $this->prepareForTests();
    }

	/**
	 * Creates the application.
	 *
	 * @return Symfony\Component\HttpKernel\HttpKernelInterface
	 */

	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__.'/../../bootstrap/start.php';
	}

    /**
     * Migrate the database
     */
    private function prepareForTests()
    {
        Artisan::call('migrate');
    }

}
