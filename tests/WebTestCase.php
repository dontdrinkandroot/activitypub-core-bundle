<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use PHPUnit\Framework\Constraint\Callback;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    /**
     * @template T
     * @param class-string<T> $class
     * @return T
     */
    protected static function getService(string $class, ?string $id = null): object
    {
        if (null === $id) {
            $id = $class;
        }
        $service = self::getContainer()->get($id);
        self::assertInstanceOf($class, $service);
        return $service;
    }

    protected function uriMatcher(string $value): Callback
    {
        return self::callback(static fn(mixed $arg): bool => Uri::fromString($value)->equals($arg));
    }
}
