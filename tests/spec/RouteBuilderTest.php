<?php

use Symfony\Component\HttpFoundation\Request;

class RouteBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideApplications
     */
    public function emptyRouteShould404($app, $kernel)
    {
        $request = Request::create('/');
        $response = $kernel->handle($request);

        $this->assertSame(404, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider provideApplications
     */
    public function getShouldDefineGetRoute($app, $kernel)
    {
        $app->get('/', function () {
            return 'get /';
        });

        $request = Request::create('/');
        $response = $kernel->handle($request);

        $this->assertSame('get /', $response->getContent());
    }

    /**
     * @test
     * @dataProvider provideApplications
     */
    public function getShouldDefineGetRoutePath($app, $kernel)
    {
        $app->get('/foo', function () {
            return 'get /foo';
        });

        $request = Request::create('/foo');
        $response = $kernel->handle($request);

        $this->assertSame('get /foo', $response->getContent());
    }

    /**
     * @test
     * @dataProvider provideApplications
     */
    public function itShouldRejectPostRequestOnGetRoute($app, $kernel)
    {
        $app->get('/foo', function () {
            return 'get /foo';
        });

        $request = Request::create('/foo', 'POST');
        $response = $kernel->handle($request);

        $this->assertSame(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider provideApplications
     */
    public function postShouldDefinePostRoute($app, $kernel)
    {
        $app->post('/foo', function () {
            return 'post /foo';
        });

        $request = Request::create('/foo', 'POST');
        $response = $kernel->handle($request);

        $this->assertSame('post /foo', $response->getContent());
    }

    public function provideApplications()
    {
        $apps = [];

        $app = new Silex\Application();
        $apps[] = [$app, $app];

        $app = new Yolo\Application();
        $apps[] = [$app, $app->getHttpKernel()];

        $app = new Tyne\Application();
        $apps[] = [$app, $app];

        return $apps;
    }
}
