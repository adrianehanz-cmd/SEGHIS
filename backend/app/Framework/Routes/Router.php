<?php

declare(strict_types=1);

namespace App\Framework\Routes;

use App\Framework\Http\Request;
use App\Framework\Http\Response;
use App\Framework\Middlewares\MiddlewareInterface;
use Closure;
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

        $basePath = '/SegHIS/backend/public';

        if (str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }

        if ($uri === '') {
            $uri = '/';
        }

        if ($uri !== '/') {
            $uri = rtrim($uri, '/');
        }

        $route = $this->routes[$method][$uri] ?? null;

        if ($route === null) {
            Response::json(null, 'Route not found.', 404);

            return;
        }

        if ($route instanceof Closure) {
            $route($request);

            return;
        }

        if (is_array($route) && array_key_exists('action', $route)) {
            $this->dispatchProtected($request, $route);

            return;
        }

        // Legacy [Controller, method] format (no middleware)
        [$controllerClass, $controllerMethod] = $route;

        $controller = $this->container->get($controllerClass);
        $controller->{$controllerMethod}();
    }

    private function dispatchProtected(Request $request, array $route): void
    {
        [$controllerClass, $controllerMethod] = $route['action'];
        $middlewareStack = $route['middleware'] ?? [];

        $handler = function (Request $request) use ($controllerClass, $controllerMethod): void {
            $controller = $this->container->get($controllerClass);
            $controller->{$controllerMethod}();
        };

        $pipeline = array_reduce(
            array_reverse($middlewareStack),
            function (callable $next, $middleware): callable {
                return function (Request $request) use ($middleware, $next): mixed {
                    $instance = $this->resolveMiddleware($middleware);

                    return $instance->handle($request, $next);
                };
            },
            $handler
        );

        $pipeline($request);
    }

    private function resolveMiddleware(mixed $middleware): MiddlewareInterface
    {
        if ($middleware instanceof MiddlewareInterface) {
            return $middleware;
        }

        if (is_string($middleware)) {
            return $this->container->get($middleware);
        }

        // Closure factory, e.g. fn () => new RoleMiddleware(['administrator'])
        return $middleware();
    }
}