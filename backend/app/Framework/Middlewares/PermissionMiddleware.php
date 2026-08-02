<?php

declare(strict_types=1);


namespace App\Framework\Middlewares;


use App\Framework\Http\Request;
use App\Framework\Http\Response;
use App\Domain\Repositories\PermissionRepositoryInterface;



final class PermissionMiddleware implements MiddlewareInterface
{


    public function __construct(
        private PermissionRepositoryInterface $permissions
    )
    {

    }



    public function handle(
        Request $request,
        callable $next
    ): mixed
    {

        $user=$request->user();


        if(!$user)
        {
            Response::json(
                null,
                "Unauthorized",
                401
            );
        }


        $permission=$_ENV['REQUIRED_PERMISSION'] ?? null;


        if(!$permission)
        {
            return $next($request);
        }


        if(!$this->permissions->userHasPermission(
            $user['id'],
            $permission
        ))
        {

            Response::json(
                null,
                "Forbidden",
                403
            );

        }


        return $next($request);

    }

}