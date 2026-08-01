<?php

declare(strict_types=1);

use App\Framework\Http\Request;
use App\Framework\Middlewares\CorsMiddleware;
use App\Framework\Middlewares\JsonMiddleware;
use App\Framework\Middlewares\SecurityHeadersMiddleware;
use App\Framework\Middlewares\RequestLoggingMiddleware;
use App\Framework\Routes\Router;
use App\Shared\Exceptions\ExceptionHandler;
use FastRoute\Dispatcher;
use Psr\Container\ContainerInterface;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$container = require dirname(__DIR__) . '/bootstrap/app.php';

$routes = require dirname(__DIR__)
    . '/app/Framework/Routes/api.php';
$request = new Request();

$router = $container->get(Router::class);

$middlewares = [
    $container->get(SecurityHeadersMiddleware::class),
    $container->get(RequestLoggingMiddleware::class),
    $container->get(CorsMiddleware::class),
    $container->get(JsonMiddleware::class),
];

$pipeline = array_reduce(
    array_reverse($middlewares),
    function (callable $next, $middleware): callable {
        return function (Request $request) use (
            $middleware,
            $next
        ): mixed {
            return $middleware->handle(
                $request,
                $next
            );
        };
    },
    function (Request $request) use ($router): void {
        $router->dispatch($request);
    }
);

try {
    $pipeline($request);
} catch (Throwable $exception) {
    ExceptionHandler::handle($exception);
}