<?php

use Carbon\Carbon;
use Illuminate\Routing\Router;
use Mockery as m;

class BasicProviderTest extends TestCase {

    public function setUp() {
        $_SERVER['HTTP_AUTHORIZATION'] = 'bob';
        $_SERVER['PHP_AUTH_USER']      = 'bob';
        $this->auth = m::mock('Illuminate\Auth\AuthManager');

        parent::setUp();
    }

    public function tearDown() {
        m::close();
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     */
    public function testMissingHeader() {
        $request = Request::create('foo','GET');
        $provider = new AvantiDevelopment\JrBank\Auth\BasicProvider($this->auth);
        $provider->authenticate($request);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     */
    public function testExpiredToken() {
        $userToken = [
            'iat'  => Carbon::now()->timestamp,
            'exp'  => Carbon::now()->timestamp,
            'user' => [
                'id'   => 1,
                'name' => "billy"
            ]
        ];
        $request = Request::create('foo','GET',[],[],[],[ 'HTTP_AUTHORIZATION' => 'Basic '. \JWT::encode( $userToken, $_ENV['token'] ) ]);
        $provider = new AvantiDevelopment\JrBank\Auth\BasicProvider($this->auth);
        $route = m::mock('Illuminate\Routing\Route');
//        $route->shouldReceive('parameter')->with('bank_id')->andReturn(1);
        $provider->authenticate($request, $route);

    }

    public function testGoodAuthentication() {
        $this->seed();
        $userToken = [
            'iat'  => Carbon::now()->timestamp,
            'exp'  => Carbon::now()->addDay()->timestamp,
            'user' => [
                'id'   => 1,
                'name' => "billy"
            ]
        ];
        $jwt     = \JWT::encode( $userToken, $_ENV['token'] );
        $request = Request::create('foo','GET',[],[],[],[ 'HTTP_AUTHORIZATION' => 'Basic '. $jwt ]);
        $provider = new AvantiDevelopment\JrBank\Auth\BasicProvider($this->auth);
        $route = m::mock('Illuminate\Routing\Route');
        $route->shouldReceive('parameter')->with('bank_id')->andReturn(1);
        $provider->authenticate($request, $route);
    }



    public function _testAuthenticate() {
        $this->seed();

//        $this->be(User::find(1));
        $this->call('GET','/api/users/me');

        $provider = new AvantiDevelopment\JrBank\Auth\BasicProvider();

        $userToken = [
            'iat'  => Carbon::now()->timestamp,
            'exp'  => Carbon::now()->addDay()->timestamp,
            'user' => [
                'id'   => 1,
                'name' => "billy"
            ]
        ];

        $request   = new \Illuminate\Http\Request(
            [  ],
            [ ],
            [ 'path' => 'api/users/me' ],
            [ 'HTTP_AUTHORIZATION' => 'bob' ],
            [ ],
            [ 'HTTP_AUTHORIZATION' => 'Basic '. \JWT::encode( $userToken, $_ENV['token'] ) ]
        );
//        $route = new \Illuminate\Routing\Route( [ 'GET' ], '/1',Route::current() );
        $router =$this->getRouter();
        $route = $router->get('foo/bar', function () {return 'hello';});
//        Route::enableFilters();
//        $apiRoutes = Route::getApiRoutes();
//$apiRoutes= $apiRoutes['v1'];
//
//        $apiRoutes = $apiRoutes->getRoutes();
//            $apiRoutes = $apiRoutes[2];
//        dd($apiRoutes);
        $provider->authenticate( $request, $route);

    }

    protected function getRouter() {
        return new Router(new Illuminate\Events\Dispatcher);
    }
}