<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Integration\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\WebTestCase;

class ObjectResolverTest extends WebTestCase
{
    public function testResolveLocalActor(): void
    {
        self::bootKernel();

        $objectResolver = self::getService(ObjectResolverInterface::class);
        $object = $objectResolver->resolveTyped(Uri::fromString('https://localhost/@service'), Actor::class);
        self::assertNotNull($object);
    }
}
