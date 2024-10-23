<?php

namespace Vlad\FishChat\core;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

readonly class Request implements ServerRequestInterface
{
    private ServerRequestInterface $baseRequest;

    public function __construct(ServerRequestInterface $baseRequest)
    {
        $this->baseRequest = $baseRequest;
    }

    public function all(): array
    {
        return (array) $this->baseRequest->getParsedBody();
    }

    public function getProtocolVersion(): string
    {
        return $this->baseRequest->getProtocolVersion();
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        return $this->baseRequest->withProtocolVersion($version);
    }

    public function getHeaders(): array
    {
        return $this->baseRequest->getHeaders();
    }

    public function hasHeader(string $name): bool
    {
        return $this->baseRequest->hasHeader($name);
    }

    public function getHeader(string $name): array
    {
        return $this->baseRequest->getHeader($name);
    }

    public function getHeaderLine(string $name): string
    {
        return $this->baseRequest->getHeaderLine($name);
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        return $this->baseRequest->withHeader($name, $value);
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        return $this->baseRequest->withAddedHeader($name, $value);
    }

    public function withoutHeader(string $name): MessageInterface
    {
        return $this->baseRequest->withoutHeader($name);
    }

    public function getBody(): StreamInterface
    {
        return $this->baseRequest->getBody();
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        return $this->baseRequest->withBody($body);
    }

    public function getRequestTarget(): string
    {
        return $this->baseRequest->getRequestTarget();
    }

    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        return $this->baseRequest->withRequestTarget($requestTarget);
    }

    public function getMethod(): string
    {
        return $this->baseRequest->getMethod();
    }

    public function withMethod(string $method): RequestInterface
    {
        return $this->baseRequest->withMethod($method);
    }

    public function getUri(): UriInterface
    {
        return $this->baseRequest->getUri();
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        return $this->baseRequest->withUri($uri, $preserveHost);
    }

    public function getServerParams(): array
    {
        return $this->baseRequest->getServerParams();
    }

    public function getCookieParams(): array
    {
        return $this->baseRequest->getCookieParams();
    }

    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        return $this->baseRequest->withCookieParams($cookies);
    }

    public function getQueryParams(): array
    {
        return $this->baseRequest->getQueryParams();
    }

    public function withQueryParams(array $query): ServerRequestInterface
    {
        return $this->baseRequest->withQueryParams($query);
    }

    public function getUploadedFiles(): array
    {
        return $this->baseRequest->getUploadedFiles();
    }

    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        return $this->baseRequest->withUploadedFiles($uploadedFiles);
    }

    public function getParsedBody()
    {
        return $this->baseRequest->getParsedBody();
    }

    public function withParsedBody($data): ServerRequestInterface
    {
        return $this->baseRequest->withParsedBody($data);
    }

    public function getAttributes(): array
    {
        return $this->baseRequest->getAttributes();
    }

    public function getAttribute(string $name, $default = null)
    {
        return $this->baseRequest->getAttribute($name, $default);
    }

    public function withAttribute(string $name, $value): ServerRequestInterface
    {
        return $this->baseRequest->withAttribute($name, $value);
    }

    public function withoutAttribute(string $name): ServerRequestInterface
    {
        return $this->baseRequest->withoutAttribute($name);
    }
}