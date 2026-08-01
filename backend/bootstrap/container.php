<?php

use App\Framework\Middlewares\CorsMiddleware;
use App\Framework\Middlewares\JsonMiddleware;
use App\Framework\Middlewares\SecurityHeadersMiddleware;
use App\Framework\Middlewares\RequestLoggingMiddleware;
use App\Framework\Routes\Router;
use App\Infrastructure\Database\Database;
use App\Infrastructure\Logging\Logger;
use App\Infrastructure\Security\JWTManager;
use DI\ContainerBuilder;
use App\Infrastructure\ExternalAPI\SegHIS\Client\SegHISHttpClient;
use App\Infrastructure\ExternalAPI\SegHIS\Services\SegHISDepartmentService;
use App\Infrastructure\ExternalAPI\SegHIS\Services\SegHISDoctorService;
use App\Infrastructure\ExternalAPI\SegHIS\Services\SegHISEncounterService;
use App\Infrastructure\ExternalAPI\SegHIS\Services\SegHISLaboratoryService;
use App\Infrastructure\ExternalAPI\SegHIS\Services\SegHISNurseService;
use App\Infrastructure\ExternalAPI\SegHIS\Services\SegHISPatientService;
use App\Infrastructure\ExternalAPI\SegHIS\Services\SegHISPrescriptionService;
use App\Infrastructure\ExternalAPI\SegHIS\Services\SegHISRadiologyService;
use App\Domain\Repositories\SegHIS\PatientRepositoryInterface;
use App\Domain\Repositories\SegHIS\DepartmentRepositoryInterface;
use App\Domain\Repositories\SegHIS\DoctorRepositoryInterface;
use App\Domain\Repositories\SegHIS\NurseRepositoryInterface;
use App\Domain\Repositories\SegHIS\EncounterRepositoryInterface;
use App\Domain\Repositories\SegHIS\LaboratoryRepositoryInterface;
use App\Domain\Repositories\SegHIS\PrescriptionRepositoryInterface;
use App\Domain\Repositories\SegHIS\RadiologyRepositoryInterface;

use App\Infrastructure\Repositories\SegHIS\SegHISPatientRepository;
use App\Infrastructure\Repositories\SegHIS\SegHISDepartmentRepository;
use App\Infrastructure\Repositories\SegHIS\SegHISDoctorRepository;
use App\Infrastructure\Repositories\SegHIS\SegHISNurseRepository;
use App\Infrastructure\Repositories\SegHIS\SegHISEncounterRepository;
use App\Infrastructure\Repositories\SegHIS\SegHISLaboratoryRepository;
use App\Infrastructure\Repositories\SegHIS\SegHISPrescriptionRepository;
use App\Infrastructure\Repositories\SegHIS\SegHISRadiologyRepository;

use App\Domain\Repositories\Auth\UserRepositoryInterface;
use App\Domain\Repositories\Auth\SessionRepositoryInterface;
use App\Infrastructure\Repositories\Auth\UserRepository;
use App\Infrastructure\Repositories\Auth\SessionRepository;
use App\Infrastructure\Logging\AuditLogger;

use function DI\autowire;

$builder = new ContainerBuilder();

$container = $builder->build();

$container->set(Database::class, function () {
    $config = require __DIR__ . '/../app/Framework/Config/database.php';

    return new Database($config);
});

$container->set(Logger::class, function () {
    $config = require __DIR__ . '/../app/Framework/Config/logging.php';

    return new Logger($config['path']);
});

$container->set(JWTManager::class, function () {
    return new JWTManager();
});

$container->set(Router::class, function () use ($container) {
    return new Router($container);
});

$container->set(CorsMiddleware::class, function () {
    return new CorsMiddleware();
});

$container->set(JsonMiddleware::class, function () {
    return new JsonMiddleware();
});

$container->set(SecurityHeadersMiddleware::class, function () {
    return new SecurityHeadersMiddleware();
});

$container->set(RequestLoggingMiddleware::class, function () use ($container) {
    return new RequestLoggingMiddleware(
            $container->get(Logger::class)
        );
    });

    $container->set(
    SegHISHttpClient::class,
    function () {
        return new SegHISHttpClient();
    }
);

$container->set(
    SegHISPatientService::class,
    function () use ($container) {
        return new SegHISPatientService(
            $container->get(SegHISHttpClient::class)
        );
    }
);

$container->set(
    SegHISDoctorService::class,
    function () use ($container) {
        return new SegHISDoctorService(
            $container->get(SegHISHttpClient::class)
        );
    }
);

$container->set(
    SegHISNurseService::class,
    function () use ($container) {
        return new SegHISNurseService(
            $container->get(SegHISHttpClient::class)
        );
    }
);

$container->set(
    SegHISDepartmentService::class,
    function () use ($container) {
        return new SegHISDepartmentService(
            $container->get(SegHISHttpClient::class)
        );
    }
);

$container->set(
    SegHISEncounterService::class,
    function () use ($container) {
        return new SegHISEncounterService(
            $container->get(SegHISHttpClient::class)
        );
    }
);

$container->set(
    SegHISLaboratoryService::class,
    function () use ($container) {
        return new SegHISLaboratoryService(
            $container->get(SegHISHttpClient::class)
        );
    }
);

$container->set(
    SegHISPrescriptionService::class,
    function () use ($container) {
        return new SegHISPrescriptionService(
            $container->get(SegHISHttpClient::class)
        );
    }
);

$container->set(
    SegHISRadiologyService::class,
    function () use ($container) {
        return new SegHISRadiologyService(
            $container->get(SegHISHttpClient::class)
        );
    }
);

$container->set(
    PatientRepositoryInterface::class,
    DI\autowire(SegHISPatientRepository::class)
);

$container->set(
    DepartmentRepositoryInterface::class,
    DI\autowire(SegHISDepartmentRepository::class)
);

$container->set(
    DoctorRepositoryInterface::class,
    DI\autowire(SegHISDoctorRepository::class)
);

$container->set(
    NurseRepositoryInterface::class,
    DI\autowire(SegHISNurseRepository::class)
);

$container->set(
    EncounterRepositoryInterface::class,
    DI\autowire(SegHISEncounterRepository::class)
);

$container->set(
    LaboratoryRepositoryInterface::class,
    DI\autowire(SegHISLaboratoryRepository::class)
);

$container->set(
    PrescriptionRepositoryInterface::class,
    DI\autowire(SegHISPrescriptionRepository::class)
);

$container->set(
    RadiologyRepositoryInterface::class,
    DI\autowire(SegHISRadiologyRepository::class)
);

$container->set(
    UserRepositoryInterface::class,
    DI\autowire(UserRepository::class)
);

$container->set(
    SessionRepositoryInterface::class,
    DI\autowire(SessionRepository::class)
);

$container->set(
    AuditLogger::class,
    function () use ($container) {
        return new AuditLogger($container->get(Database::class));
    }
);

return $container;