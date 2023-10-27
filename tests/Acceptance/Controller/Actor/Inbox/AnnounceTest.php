<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance\Controller\Actor\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Note;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObjectsCollection;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Share\InteractionServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Service\Object\MockObjectProvider;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\UriMatcherTrait;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\WebTestCase;

class AnnounceTest extends WebTestCase
{
    use UriMatcherTrait;

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

        $mockObjectProvider = $this->createMock(MockObjectProvider::class);
        $mockObjectProvider
            ->expects(self::once())
            ->method('provide')
            ->with(self::uriMatcher('https://localhost/@person/note/1'))
            ->willReturn($note);
        self::getContainer()->set(MockObjectProvider::class, $mockObjectProvider);

        $shareServiceMock = $this->createMock(InteractionServiceInterface::class);
        $shareServiceMock
            ->expects(self::once())
            ->method('incoming')
            ->with(
                self::uriMatcher('http://localhost/@service/activities/1'),
                'Announce',
                self::uriMatcher('https://localhost/@service'),
                self::uriMatcher('https://localhost/@person/note/1'),
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
