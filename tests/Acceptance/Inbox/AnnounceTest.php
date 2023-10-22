<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\LocalObjectResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Share\ShareServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\WebTestCase;

class AnnounceTest extends WebTestCase
{
    public function testAnnounce(): void
    {
        self::bootKernel();

        $activityPubClient = self::getService(ActivityPubClientInterface::class);
        $localActorService = self::getService(LocalActorServiceInterface::class);
        $service = $localActorService->findLocalActorByUsername('service');
        self::assertNotNull($service);
        $signKey = $localActorService->getSignKey($service);

        $json = <<<JSON
{
    "@context": "https://www.w3.org/ns/activitystreams",
    "id": "http://localhost/@service/activities/1",
    "type": "Announce",
    "actor": "http://localhost/@service",
    "object": "http://localhost/@person/note/1"
}
JSON;

        $localObjectResolverMock = $this->createMock(LocalObjectResolverInterface::class);
        $localObjectResolverMock
            ->expects(self::once())
            ->method('hasObject')
            ->with($this->uriMatcher('http://localhost/@person/note/1'))
            ->willReturn(true);
        self::getContainer()->set(LocalObjectResolverInterface::class, $localObjectResolverMock);

        $shareServiceMock = $this->createMock(ShareServiceInterface::class);
        $shareServiceMock
            ->expects(self::once())
            ->method('shared')
            ->with(
                $this->uriMatcher('http://localhost/@service'),
                $this->uriMatcher('http://localhost/@person/note/1'),
            );
        self::getContainer()->set(ShareServiceInterface::class, $shareServiceMock);

        $response = $activityPubClient->request(
            method: 'POST',
            uri: Uri::fromString('http://localhost/@person/inbox'),
            content: $json,
            signKey: $signKey
        );
    }
}
