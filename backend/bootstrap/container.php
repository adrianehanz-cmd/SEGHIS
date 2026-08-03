<?php

declare(strict_types=1);

use App\Domain\Repositories\UserRepositoryInterface;

use App\Domain\Repositories\SegHIS\PatientRepositoryInterface;
use App\Domain\Repositories\SegHIS\DepartmentRepositoryInterface;
use App\Domain\Repositories\SegHIS\DoctorRepositoryInterface;
use App\Domain\Repositories\SegHIS\NurseRepositoryInterface;
use App\Domain\Repositories\SegHIS\EncounterRepositoryInterface;
use App\Domain\Repositories\SegHIS\LaboratoryRepositoryInterface;

use App\Infrastructure\Database\Database;
use App\Infrastructure\Logging\Logger;
use App\Infrastructure\Security\JwtService;

use App\Infrastructure\Repositories\MySQLUserRepository;

use App\Infrastructure\Repositories\SegHISPatientRepository;
use App\Infrastructure\Repositories\SegHISDepartmentRepository;
use App\Infrastructure\Repositories\SegHISDoctorRepository;
use App\Infrastructure\Repositories\SegHISNurseRepository;
use App\Infrastructure\Repositories\SegHISEncounterRepository;
use App\Infrastructure\Repositories\SegHISLaboratoryRepository;

use App\Infrastructure\ExternalAPI\SegHIS\SegHISClient;
use App\Infrastructure\ExternalAPI\SegHIS\SegHISPatientService;
use App\Infrastructure\ExternalAPI\SegHIS\SegHISDepartmentService;
use App\Infrastructure\ExternalAPI\SegHIS\SegHISDoctorService;
use App\Infrastructure\ExternalAPI\SegHIS\SegHISNurseService;
use App\Infrastructure\ExternalAPI\SegHIS\SegHISEncounterService;
use App\Infrastructure\ExternalAPI\SegHIS\SegHISLaboratoryService;

use App\Application\UseCases\Patients\GetPatientsUseCase;
use App\Application\UseCases\Departments\GetDepartmentsUseCase;
use App\Application\UseCases\Doctors\GetDoctorsUseCase;
use App\Application\UseCases\Nurses\GetNursesUseCase;
use App\Application\UseCases\Nurses\GetEncounterUseCase;
use App\Application\UseCases\Nurses\GetLaboratoryUseCase;

use DI\ContainerBuilder;

$builder = new ContainerBuilder();

$container = $builder->build();

/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
*/

$container->set(\PDO::class, function () {
    return Database::connect();
});

/*
|--------------------------------------------------------------------------
| Logger
|--------------------------------------------------------------------------
*/

$container->set(Logger::class, function () {

    $config = require __DIR__ .
        '/../app/Framework/Config/logging.php';

    return new Logger(
        $config['path']
    );

});

/*
|--------------------------------------------------------------------------
| JWT
|--------------------------------------------------------------------------
*/

$container->set(
    JwtService::class,
    fn() => new JwtService(
        $_ENV['JWT_SECRET']
    )
);

/*
|--------------------------------------------------------------------------
| SegHIS Client
|--------------------------------------------------------------------------
*/

$container->set(
    SegHISClient::class,
    fn() => new SegHISClient()
);

/*
|--------------------------------------------------------------------------
| SegHIS Services
|--------------------------------------------------------------------------
*/

$container->set(
    SegHISPatientService::class,
    fn($c) => new SegHISPatientService(
        $c->get(SegHISClient::class)
    )
);

$container->set(
    SegHISDepartmentService::class,
    fn($c) => new SegHISDepartmentService(
        $c->get(SegHISClient::class)
    )
);

$container->set(
    SegHISDoctorService::class,
    fn($c) => new SegHISDoctorService(
        $c->get(SegHISClient::class)
    )
);

$container->set(
    SegHISNurseService::class,
    fn($c) => new SegHISNurseService(
        $c->get(SegHISClient::class)
    )
);

$container->set(
    SegHISEncounterService::class,
    fn($c) => new SegHISEncounterService(
        $c->get(SegHISClient::class)
    )
);

$container->set(
    SegHISLaboratoryService::class,
    fn($c) => new SegHISLaboratoryService(
        $c->get(SegHISClient::class)
    )
);

/*
|--------------------------------------------------------------------------
| User Repository
|--------------------------------------------------------------------------
*/

$container->set(
    UserRepositoryInterface::class,
    fn($c) => new MySQLUserRepository(
        $c->get(\PDO::class)
    )
);

/*
|--------------------------------------------------------------------------
| SegHIS Repositories
|--------------------------------------------------------------------------
*/

$container->set(
    PatientRepositoryInterface::class,
    fn($c) => new SegHISPatientRepository(
        $c->get(SegHISPatientService::class)
    )
);

$container->set(
    DepartmentRepositoryInterface::class,
    fn($c) => new SegHISDepartmentRepository(
        $c->get(SegHISDepartmentService::class)
    )
);

$container->set(
    DoctorRepositoryInterface::class,
    fn($c) => new SegHISDoctorRepository(
        $c->get(SegHISDoctorService::class)
    )
);

$container->set(
    NurseRepositoryInterface::class,
    fn($c) => new SegHISNurseRepository(
        $c->get(SegHISNurseService::class)
    )
);

$container->set(
    EncounterRepositoryInterface::class,
    fn($c) => new SegHISEncounterRepository(
        $c->get(SegHISEncounterService::class)
    )
);

$container->set(
    LaboratoryRepositoryInterface::class,
    fn($c) => new SegHISLaboratoryRepository(
        $c->get(SegHISLaboratoryService::class)
    )
);


/*
|--------------------------------------------------------------------------
| Use Cases
|--------------------------------------------------------------------------
*/

$container->set(
    GetPatientsUseCase::class,
    fn($c) => new GetPatientsUseCase(
        $c->get(PatientRepositoryInterface::class)
    )
);

$container->set(
    GetDepartmentsUseCase::class,
    fn($c) => new GetDepartmentsUseCase(
        $c->get(DepartmentRepositoryInterface::class)
    )
);

$container->set(
    GetDoctorsUseCase::class,
    fn($c) => new GetDoctorsUseCase(
        $c->get(DoctorRepositoryInterface::class)
    )
);

$container->set(
    GetNursesUseCase::class,
    fn($c) => new GetNursesUseCase(
        $c->get(NurseRepositoryInterface::class)
    )
);

$container->set(
    GetEncounterUseCase::class,
    fn($c) => new GetEncounterUseCase(
        $c->get(EncounterRepositoryInterface::class)
    )
);

$container->set(
    GetLaboratoryUseCase::class,
    fn($c) => new GetLaboratoryUseCase(
        $c->get(LaboratoryRepositoryInterface::class)
    )
);

return $container;