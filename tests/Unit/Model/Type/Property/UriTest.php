<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Unit\Model\Type\Property;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use PHPUnit\Framework\TestCase;

class UriTest extends TestCase
{
    public function testFromString(): void
    {
        $uri = Uri::fromString('https://example.com:8080/path/to/resource?query=string#fragment');
        self::assertEquals('https', $uri->scheme);
        self::assertEquals('example.com', $uri->host);
        self::assertEquals(8080, $uri->port);
        self::assertEquals('/path/to/resource', $uri->path);
        self::assertEquals('query=string', $uri->query);
        self::assertEquals('fragment', $uri->fragment);
        self::assertNull($uri->user);
        self::assertNull($uri->pass);
        self::assertEquals('example.com:8080', $uri->getAuthority());
        self::assertEquals('/path/to/resource?query=string#fragment', $uri->getPathWithQueryAndFragment());
    }

    public function testWithFragment(): void
    {
        $uri = Uri::fromString('https://example.com:8080/path/to/resource?query=string#fragment');
        $uri = $uri->withFragment('new-fragment');
        self::assertEquals('/path/to/resource?query=string#new-fragment', $uri->getPathWithQueryAndFragment());
    }

    public function testWithAppendedPath(): void
    {
        $uri = Uri::fromString('https://example.com:8080/path/to/resource?query=string#fragment');
        $uri = $uri->withAppendedPath('new-path');
        self::assertEquals('/path/to/resource/new-path?query=string#fragment', $uri->getPathWithQueryAndFragment());
    }
}
