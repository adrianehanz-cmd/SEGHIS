<?php

namespace App\Framework\Middlewares;

use App\Framework\Http\Request;

interface MiddlewareInterface
{
    public function handle(
        Request $request,
        callable $next
    ): mixed;
}