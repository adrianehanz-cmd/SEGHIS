<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Infrastructure\ExternalAPI\SegHIS\SegHISClient;


$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();


$client = new SegHISClient();


$result = $client->get(
    'doctor/show'
);


print_r($result);