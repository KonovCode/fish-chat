<?php

namespace Core;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Vlad\FishChat\core\Response;

class ResponseTest extends TestCase
{
    private Response $response;
    protected function setUp(): void
    {
        $this->response = new Response();
    }

    public function testCreateReturnsResponseInterface()
    {
        $result = $this->response->create(200, 'OK', ['Content-Type' => 'text/plain']);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testCreateSetsCorrectStatus()
    {
        $result = $this->response->create(404, 'Not Found');

        $this->assertEquals(404, $result->getStatusCode());
    }

    public function testCreateSetsCorrectBody()
    {
        $result = $this->response->create(200, 'Hello World');

        $this->assertEquals('Hello World', (string) $result->getBody());
    }

    public function testCreateSetsCorrectHeaders()
    {
        $result = $this->response->create(200, '', ['Content-Type' => 'application/json']);

        $this->assertEquals(['application/json'], $result->getHeader('Content-Type'));
    }
}