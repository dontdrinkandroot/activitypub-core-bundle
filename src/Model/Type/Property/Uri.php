<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property;

use InvalidArgumentException;
use Override;
use Stringable;

class Uri implements Stringable
{
    private function __construct(
        public readonly ?string $scheme,
        public readonly ?string $host,
        public readonly ?int $port,
        public readonly ?string $user,
        public readonly ?string $pass,
        public readonly ?string $query,
        public readonly ?string $path,
        public readonly ?string $fragment,
    ) {
    }

    public static function fromString(string $uri): Uri
    {
        $uriParts = parse_url($uri);
        if (false === $uriParts) {
            throw new InvalidArgumentException('Invalid URI: ' . $uri);
        }

        return new Uri(
            scheme: $uriParts['scheme'] ?? null,
            host: $uriParts['host'] ?? null,
            port: $uriParts['port'] ?? null,
            user: $uriParts['user'] ?? null,
            pass: $uriParts['pass'] ?? null,
            query: $uriParts['query'] ?? null,
            path: $uriParts['path'] ?? null,
            fragment: $uriParts['fragment'] ?? null
        );
    }

    public function getAuthority(): ?string
    {
        $authority = '';
        if ($this->host !== null) {
            $authority .= $this->host;
        }
        if ($this->port !== null) {
            $authority .= ':' . $this->port;
        }
        if ($this->user !== null) {
            $authority .= $this->user;
        }
        if ($this->pass !== null) {
            $authority .= ':' . $this->pass;
        }

        return $authority;
    }

    public function getPathWithQueryAndFragment(): ?string
    {
        $path = $this->path;
        if ($this->query !== null) {
            $path .= '?' . $this->query;
        }
        if ($this->fragment !== null) {
            $path .= '#' . $this->fragment;
        }

        return $path;
    }

    public function toString(): string
    {
        return $this->__toString();
    }

    #[Override]
    public function __toString(): string
    {
        $uri = '';
        if ($this->scheme !== null) {
            $uri .= $this->scheme . ':';
        }
        if ($this->host !== null) {
            $uri .= '//' . $this->host;
        }
        if ($this->port !== null) {
            $uri .= ':' . $this->port;
        }
        if ($this->user !== null) {
            $uri .= $this->user;
        }
        if ($this->pass !== null) {
            $uri .= ':' . $this->pass;
        }
        if ($this->path !== null) {
            $uri .= $this->path;
        }
        if ($this->query !== null) {
            $uri .= '?' . $this->query;
        }
        if ($this->fragment !== null) {
            $uri .= '#' . $this->fragment;
        }

        return $uri;
    }

    public function withFragment(?string $fragment): Uri
    {
        return new Uri(
            scheme: $this->scheme,
            host: $this->host,
            port: $this->port,
            user: $this->user,
            pass: $this->pass,
            query: $this->query,
            path: $this->path,
            fragment: $fragment
        );
    }

    public function withAppendedPath(string $string): Uri
    {
        return new Uri(
            scheme: $this->scheme,
            host: $this->host,
            port: $this->port,
            user: $this->user,
            pass: $this->pass,
            query: $this->query,
            path: rtrim($this->path ?? '', '/') . '/' . ltrim($string, '/'),
            fragment: $this->fragment
        );
    }

    public function equals(mixed $other): bool
    {
        return ($other instanceof self) && (string)$this === (string)$other;
    }
}
