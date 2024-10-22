<?php

namespace Core;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Vlad\FishChat\core\Response;

class ResponseTest extends TestCase
{
    public function testCreateReturnsResponseInterface()
    {
        $response = new Response();
        $result = $response->create(200, 'OK', ['Content-Type' => 'text/plain']);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testCreateSetsCorrectStatus()
    {
        $response = new Response();
        $result = $response->create(404, 'Not Found');

        $this->assertEquals(404, $result->getStatusCode());
    }

    public function testCreateSetsCorrectBody()
    {
        $response = new Response();
        $result = $response->create(200, 'Hello World');

        $this->assertEquals('Hello World', (string) $result->getBody());
    }

    public function testCreateSetsCorrectHeaders()
    {
        $response = new Response();
        $result = $response->create(200, '', ['Content-Type' => 'application/json']);

        $this->assertEquals(['application/json'], $result->getHeader('Content-Type'));
    }
}