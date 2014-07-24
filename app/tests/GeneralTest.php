<?php

class GeneralTest extends TestCase {

	/**
     * @expectedException  Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 *
	 * @return void
	 */
	public function testBadRoute()
	{
		$crawler = $this->client->request('GET', '/');
	}

}