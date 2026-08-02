<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$container = require dirname(__DIR__) . '/bootstrap/app.php';

$repository = $container->get(
    \App\Domain\Repositories\UserRepositoryInterface::class
);

$user = $repository->findByUsername('admin');

if ($user) {
    echo $user->getFullName();
} else {
    echo "User not found";
}