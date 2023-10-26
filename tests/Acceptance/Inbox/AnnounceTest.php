<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Note;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObjectsCollection;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Share\InteractionServiceInterface;
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
    "actor": "https://localhost/@service",
    "object": "https://localhost/@person/note/1"
}
JSON;

        $note = new Note();
        $note->id = Uri::fromString('https://localhost/@person/note/1');
        $note->attributedTo = LinkableObjectsCollection::singleLinkFromUri(
            Uri::fromString('https://localhost/@person')
        );

        $objectResolverMock = $this->createMock(ObjectResolverInterface::class);
        $objectResolverMock
            ->expects(self::once())
            ->method('resolve')
            ->with(
                self::callback(
                    fn($argument) => $argument instanceof LinkableObject && $argument->getId()->equals(
                            Uri::fromString('https://localhost/@person/note/1')
                        )
                )
            )
            ->willReturn($note);
        self::getContainer()->set(ObjectResolverInterface::class, $objectResolverMock);

        $shareServiceMock = $this->createMock(InteractionServiceInterface::class);
        $shareServiceMock
            ->expects(self::once())
            ->method('incoming')
            ->with(
                $this->uriMatcher('http://localhost/@service/activities/1'),
                'Announce',
                $this->uriMatcher('https://localhost/@service'),
                $this->uriMatcher('https://localhost/@person/note/1'),
            );
        self::getContainer()->set(InteractionServiceInterface::class, $shareServiceMock);

        $response = $activityPubClient->request(
            method: 'POST',
            uri: Uri::fromString('https://localhost/@person/inbox'),
            content: $json,
            signKey: $signKey
        );
    }
}
