<?php

namespace Tyne;

use Aura\Router\Map;
use Aura\Router\DefinitionFactory;
use Aura\Router\RouteFactory;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class Application implements HttpKernelInterface
{
    private $map;

    public function __construct()
    {
        $this->map = new Map(new DefinitionFactory, new RouteFactory);
    }

    public function get($path, $controller)
    {
        $this->match($path, $controller, 'GET');
    }

    public function post($path, $controller)
    {
        $this->match($path, $controller, 'POST');
    }

    public function put($path, $controller)
    {
        $this->match($path, $controller, 'PUT');
    }

    public function delete($path, $controller)
    {
        $this->match($path, $controller, 'DELETE');
    }

    public function match($path, $controller, $method)
    {
        $this->map->add(null, $path, [
            'values' => [
                'controller' => $controller,
            ],
            'method' => $method ? [$method] : [],
        ]);
    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $route = $this->map->match($request->getPathInfo(), $request->server->all());
        if (!$route) {
            return $this->otherMethodMatches($request)
                ? new Response('Method not supported', 405)
                : new Response('Not found', 404);
        }

        $controller = $route->values['controller'];
        $response = $controller($request);
        $response = ($response instanceof Response) ? $response : new Response($response);

        return $response;
    }

    private function otherMethodMatches(Request $request)
    {
        $otherMethods = array_diff(['GET', 'POST', 'PUT', 'DELETE'], [$request->getMethod()]);
        foreach ($otherMethods as $method) {
            $otherRequest = clone $request;
            $otherRequest->setMethod($method);

            if ($this->map->match($otherRequest->getPathInfo(), $otherRequest->server->all())) {
                return true;
            }
        }

        return false;
    }
}
