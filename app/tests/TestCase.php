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
        $this->resetEvents();
    }

    private function resetEvents()
    {
        // Get all models in the Model directory
        $pathToModels = app_path().'/models';   // <- Change this to your model directory
        $files = File::files($pathToModels);

        // Remove the directory name and the .php from the filename
        $files = str_replace($pathToModels.'/', '', $files);
        $files = str_replace('.php', '', $files);

        // Remove "BaseModel" as we dont want to boot that moodel
        if(($key = array_search('BaseModel', $files)) !== false) {
            unset($files[$key]);
        }
        // Remove "BaseModel" as we dont want to boot that moodel
        if(($key = array_search('BasicProvider', $files)) !== false) {
            unset($files[$key]);
        }

        // Reset each model event listeners.
        foreach ($files as $model) {

            // Flush any existing listeners.
            call_user_func(array('AvantiDevelopment\JrBank\Models\\' . $model, 'flushEventListeners'));

            // Reregister them.
            call_user_func(array('AvantiDevelopment\JrBank\Models\\' .$model, 'boot'));
        }
    }

}
