<?php

namespace App\Infrastructure\Logging;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger as MonologLogger;

class Logger
{
    private string $logPath;

    public function __construct(string $logPath)
    {
        $this->logPath = rtrim($logPath, DIRECTORY_SEPARATOR);

        if (!is_dir($this->logPath)) {
            mkdir($this->logPath, 0755, true);
        }
    }

    public function application(): MonologLogger
    {
        return $this->createLogger(
            'application',
            'application.log',
            Level::Debug
        );
    }

    public function api(): MonologLogger
    {
        return $this->createLogger(
            'api',
            'api.log',
            Level::Info
        );
    }

    public function error(): MonologLogger
    {
        return $this->createLogger(
            'error',
            'error.log',
            Level::Error
        );
    }

    public function audit(): MonologLogger
    {
        return $this->createLogger(
            'audit',
            'audit.log',
            Level::Info
        );
    }

    private function createLogger(
        string $channel,
        string $filename,
        Level $level
    ): MonologLogger {
        $logger = new MonologLogger($channel);

        $logger->pushHandler(
            new StreamHandler(
                $this->logPath . DIRECTORY_SEPARATOR . $filename,
                $level
            )
        );

        return $logger;
    }
}