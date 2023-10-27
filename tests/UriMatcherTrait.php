<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use PHPUnit\Framework\Constraint\Callback;

trait UriMatcherTrait
{
    protected static function uriMatcher(string $value): Callback
    {
        return self::callback(static fn(mixed $arg): bool => Uri::fromString($value)->equals($arg));
    }
}
