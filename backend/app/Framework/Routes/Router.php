<?php

declare(strict_types=1);

namespace App\Framework\Routes;

use App\Framework\Http\Request;
use App\Framework\Http\Response;
use DI\Container;

class Router
{
    private array $routes;

    public function __construct(
        private readonly Container $container
    ) {
        $this->routes = require __DIR__ . '/api.php';
    }

    public function dispatch(Request $request): void
    {
        $method = strtoupper($request->method());

        $uri = $request->uri();

        /*
        |--------------------------------------------------------------------------
        | Remove base folder when running under XAMPP
        |--------------------------------------------------------------------------
        */

        $basePath = '/SegHIS/backend/public';

        if (str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }

        /*
        |--------------------------------------------------------------------------
        | Normalize URI
        |--------------------------------------------------------------------------
        */

        if ($uri === '') {
            $uri = '/';
        }

        if ($uri !== '/') {
            $uri = rtrim($uri, '/');
        }

        $route = $this->routes[$method][$uri] ?? null;

        if ($route === null) {
            Response::json(
                null,
                'Route not found.',
                404
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Controller Route
        |--------------------------------------------------------------------------
        */

        if (is_array($route)) {

            [$controllerClass, $controllerMethod] = $route;

            $controller = $this->container->get($controllerClass);

            $controller->{$controllerMethod}();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Closure Route
        |--------------------------------------------------------------------------
        */

        $route($request);
    }
}