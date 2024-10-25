<?php

namespace Vlad\FishChat\core;

use ErrorException;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

readonly class ErrorHandler
{
    public function __construct(private Logger $logger)
    {
        $handler = new StreamHandler(__DIR__ . '/../../log/monolog.log', Level::Error);

        $handler->setFormatter(new JsonFormatter());

        $this->logger->pushHandler($handler);

        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    public function handleError(int $severity, string $message, string $file, int $line): void {
        throw new ErrorException($message, 0, $severity, $file, $line);
    }

    public function handleException(\Throwable $exception): void {
        $this->logger->error($exception->getMessage(), ['exception' => $exception]);

        try {
            $response = $this->getErrorResponse($exception);
            echo json_encode($response);
        } catch (\Exception $e) {
            $this->logger->error('Error while creating error response', ['exception' => $e]);
            echo json_encode(['message' => 'An unexpected error occurred.']);
        }
    }

    public function handleShutdown(): void {
        $error = error_get_last();
        if ($error !== null && ($error['type'] === E_ERROR || $error['type'] === E_PARSE)) {
            $this->logger->error('Fatal error', $error);
            $response = $_ENV['APP_ENV'] === 'production'
                ? ['message' => 'A fatal error occurred. Please try again later.']
                : $error;

            echo json_encode($response);
        }
    }

    private function getErrorResponse(\Throwable $exception): array {
        if ($_ENV['APP_ENV'] === 'production') {
            return ['message' => 'Something went wrong. Please try again later.'];
        } else {
            return [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace(),
            ];
        }
    }
}
