<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowerStorageInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowingStorageInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\WebTestCase;
use RuntimeException;

class FollowTest extends WebTestCase
{
    public function testMissingActor(): void
    {
        $activityPubClient = self::getService(ActivityPubClientInterface::class);
        $localActorService = self::getService(LocalActorServiceInterface::class);
        $person = $localActorService->findLocalActorByUsername('person');
        self::assertNotNull($person);
        $signKey = $localActorService->getSignKey($person);

        $json = <<<JSON
{
    "@context": "https://www.w3.org/ns/activitystreams",
    "id": "http://localhost/@person/activities/12345",
    "type": "Follow",
    "actor": "http://localhost/@person",
    "object": "http://localhost/@missing"
}
JSON;

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Actor not found');
        $activityPubClient->request(
            method: 'POST',
            uri: Uri::fromString('http://localhost/@missing/inbox'),
            content: json_encode([
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'type' => 'Follow',
                'actor' => 'http://localhost/@person',
                'object' => 'http://localhost/@missing',
            ], JSON_THROW_ON_ERROR),
            signKey: $signKey
        );
    }

    public function testFollow(): void
    {
        self::bootKernel();

        $activityPubClient = self::getService(ActivityPubClientInterface::class);
        $localActorService = self::getService(LocalActorServiceInterface::class);
        $person = $localActorService->findLocalActorByUsername('person');
        self::assertNotNull($person);
        $signKey = $localActorService->getSignKey($person);

        $json = <<<JSON
{
    "@context": "https://www.w3.org/ns/activitystreams",
    "id": "http://localhost/@person/activities/12345",
    "type": "Follow",
    "actor": "http://localhost/@person",
    "object": "http://localhost/@service"
}
JSON;

        $followServiceMock = $this->createMock(FollowerStorageInterface::class);
        $followServiceMock
            ->expects(self::once())
            ->method('add')
            ->with(
                self::callback(fn($argument) => $argument instanceof LocalActorInterface
                    && $argument->getUsername() === 'service'),
                self::callback(fn($argument) => $argument instanceof Uri
                    && $argument->__toString() === 'http://localhost/@person')
            );
        self::getContainer()->set(FollowerStorageInterface::class, $followServiceMock);

        $activityPubClient->request(
            method: 'POST',
            uri: Uri::fromString('http://localhost/@service/inbox'),
            content: $json,
            signKey: $signKey
        );
    }

    public function testAcceptFollow(): void
    {
        self::bootKernel();

        $activityPubClient = self::getService(ActivityPubClientInterface::class);
        $localActorService = self::getService(LocalActorServiceInterface::class);
        $person = $localActorService->findLocalActorByUsername('person');
        self::assertNotNull($person);
        $signKey = $localActorService->getSignKey($person);

        $json = <<<JSON
{
    "@context": "https://www.w3.org/ns/activitystreams",
    "id": "http://localhost/@person/activities/12346",
    "type": "Accept",
    "actor": "http://localhost/@person",
    "object": {
        "id": "http://localhost/@service/activities/12345",
        "type": "Follow",
        "actor": "http://localhost/@service",
        "object": "http://localhost/@person"
    }
}
JSON;

        $followServiceMock = $this->createMock(FollowingStorageInterface::class);
        $followServiceMock
            ->expects(self::once())
            ->method('accept')
            ->with(
                self::callback(fn($argument) => $argument instanceof LocalActorInterface
                    && $argument->getUsername() === 'service'),
                self::callback(fn($argument) => $argument instanceof Uri
                    && $argument->__toString() === 'http://localhost/@person')
            );
        self::getContainer()->set(FollowingStorageInterface::class, $followServiceMock);

        $activityPubClient->request(
            method: 'POST',
            uri: Uri::fromString('http://localhost/@service/inbox'),
            content: $json,
            signKey: $signKey
        );
    }

    public function testUndoFollow(): void
    {
        self::bootKernel();

        $activityPubClient = self::getService(ActivityPubClientInterface::class);
        $localActorService = self::getService(LocalActorServiceInterface::class);
        $person = $localActorService->findLocalActorByUsername('person');
        self::assertNotNull($person);
        $signKey = $localActorService->getSignKey($person);

        $json = <<<JSON
{
    "@context": "https://www.w3.org/ns/activitystreams",
    "id": "http://localhost/@person/activities/12347",
    "type": "Undo",
    "actor": "http://localhost/@person",
    "object": {
        "id": "http://localhost/@person/activities/12345",
        "type": "Follow",
        "actor": "http://localhost/@person",
        "object": "http://localhost/@service"
    }
}
JSON;

        $followServiceMock = $this->createMock(FollowerStorageInterface::class);
        $followServiceMock
            ->expects(self::once())
            ->method('remove')
            ->with(
                self::callback(fn($argument) => $argument instanceof LocalActorInterface
                    && $argument->getUsername() === 'service'),
                self::callback(fn($argument) => $argument instanceof Uri
                    && $argument->__toString() === 'http://localhost/@person')
            );
        self::getContainer()->set(FollowerStorageInterface::class, $followServiceMock);

        $activityPubClient->request(
            method: 'POST',
            uri: Uri::fromString('http://localhost/@service/inbox'),
            content: $json,
            signKey: $signKey
        );
    }
}
