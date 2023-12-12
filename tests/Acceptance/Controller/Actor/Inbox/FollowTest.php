<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance\Controller\Actor\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\ActivityPubClientException;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Direction;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowResponseMode;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowService;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowStorageInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\WebTestCase;

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

        $this->expectException(ActivityPubClientException::class);
        $this->expectExceptionMessage('Actor not found');
        $this->expectExceptionCode(404);
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

    public function testFollowManual(): void
    {
        self::bootKernel();

        $followStorageMock = $this->createMock(FollowStorageInterface::class);
        $followStorageMock
            ->expects(self::once())
            ->method('add')
            ->with(
                self::callback(fn($argument) => $argument instanceof LocalActorInterface
                    && $argument->getUsername() === 'service'),
                self::callback(fn($argument) => $argument instanceof Uri
                    && $argument->__toString() === 'https://localhost/@person'),
                self::callback(fn($argument) => $argument instanceof Direction
                    && $argument === Direction::INCOMING)
            );
        self::getContainer()->set(FollowStorageInterface::class, $followStorageMock);

        $activityPubClient = self::getService(ActivityPubClientInterface::class);
        $localActorService = self::getService(LocalActorServiceInterface::class);
        $followService = self::getService(FollowServiceInterface::class);
        self::assertInstanceOf(FollowService::class, $followService);
        $followService->setFollowResponseMode(FollowResponseMode::MANUAL);

        $person = $localActorService->findLocalActorByUsername('person');
        self::assertNotNull($person);
        $signKey = $localActorService->getSignKey($person);

        $json = <<<JSON
{
    "@context": "https://www.w3.org/ns/activitystreams",
    "id": "https://localhost/@person/activities/12345",
    "type": "Follow",
    "actor": "https://localhost/@person",
    "object": "https://localhost/@service"
}
JSON;

        $activityPubClient->request(
            method: 'POST',
            uri: Uri::fromString('https://localhost/@service/inbox'),
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
    "actor": "https://localhost/@person",
    "object": {
        "id": "http://localhost/@service/activities/12345",
        "type": "Follow",
        "actor": "https://localhost/@service",
        "object": "https://localhost/@person"
    }
}
JSON;

        $followStorageMock = $this->createMock(FollowStorageInterface::class);
        $followStorageMock
            ->expects(self::once())
            ->method('accept')
            ->with(
                self::callback(fn($argument) => $argument instanceof LocalActorInterface
                    && $argument->getUsername() === 'service'),
                self::callback(fn($argument) => $argument instanceof Uri
                    && $argument->__toString() === 'https://localhost/@person'),
                self::callback(fn($argument) => $argument instanceof Direction
                    && $argument === Direction::OUTGOING)
            );
        self::getContainer()->set(FollowStorageInterface::class, $followStorageMock);

        $activityPubClient->request(
            method: 'POST',
            uri: Uri::fromString('https://localhost/@service/inbox'),
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
    "actor": "https://localhost/@person",
    "object": {
        "id": "https://localhost/@person/activities/12345",
        "type": "Follow",
        "actor": "https://localhost/@person",
        "object": "https://localhost/@service"
    }
}
JSON;

        $followStorageMock = $this->createMock(FollowStorageInterface::class);
        $followStorageMock
            ->expects(self::once())
            ->method('remove')
            ->with(
                self::callback(fn($argument) => $argument instanceof LocalActorInterface
                    && $argument->getUsername() === 'service'),
                self::callback(fn($argument) => $argument instanceof Uri
                    && $argument->__toString() === 'https://localhost/@person'),
                self::callback(fn($argument) => $argument instanceof Direction
                    && $argument === Direction::INCOMING)
            );
        self::getContainer()->set(FollowStorageInterface::class, $followStorageMock);

        $activityPubClient->request(
            method: 'POST',
            uri: Uri::fromString('https://localhost/@service/inbox'),
            content: $json,
            signKey: $signKey
        );
    }
}
