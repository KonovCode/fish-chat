<?php

namespace Core;

use ErrorException;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Vlad\FishChat\core\ErrorHandler;

class ErrorHandlerTest extends TestCase
{
    private Logger $loggerMock;
    private ErrorHandler $errorHandler;

    protected function setUp(): void
    {
        $this->loggerMock = $this->createMock(Logger::class);

        $this->errorHandler = new ErrorHandler($this->loggerMock);
    }

    public function tearDown(): void
    {
        restore_error_handler();
        restore_exception_handler();
    }


    public function testHandleErrorThrowsErrorException()
    {
        $this->expectException(ErrorException::class);

        $this->errorHandler->handleError(E_WARNING, 'Test error', __FILE__, __LINE__);
    }

    public function testHandleExceptionReturnCorrectResponseInLocal()
    {
        $_ENV['APP_ENV'] = 'local';

        $exception = new \Exception('Test exception', 500);

        ob_start();
        $this->errorHandler->handleException($exception);
        $output = ob_get_clean();

        $expected = json_encode([
            'code' => 500,
            'message' => 'Test exception',
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
        ]);

        $this->assertEquals($expected, $output);
    }

    public function testHandleExceptionReturnCorrectResponseInProduction()
    {
        $_ENV['APP_ENV'] = 'production';

        $exception = new \Exception('Test exception', 500);

        ob_start();
        $this->errorHandler->handleException($exception);
        $output = ob_get_clean();

        $expected = json_encode([
            'message' => 'Something went wrong. Please try again later.',
        ]);

        $this->assertEquals($expected, $output);
    }
}