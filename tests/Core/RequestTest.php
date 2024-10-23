<?php

namespace Core;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Vlad\FishChat\core\Request;

class RequestTest extends TestCase
{
    private ServerRequestInterface $baseRequest;

    private Request $request;

    protected function setUp(): void
    {
        $this->baseRequest = $this->createMock(ServerRequestInterface::class);

        $this->request = new Request($this->baseRequest);
    }

    public function testGetAll(): void
    {
        $formData = ['name' => 'name', 'email' => 'email@gmail.com', 'age' => 30];

        $this->baseRequest->method('getParsedBody')->willReturn($formData);

        $this->assertEquals($formData, $this->request->all());
    }
}