<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowerStorageInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\WebTestCase;

class FollowersActionTest extends WebTestCase
{
    public function testMainPage(): void
    {
        $client = self::createClient();
        $client->catchExceptions(false);

        $followerStorage = $this->createMock(FollowerStorageInterface::class);
        $followerStorage->expects(self::once())->method('count')->willReturn(9);
        self::getContainer()->set(FollowerStorageInterface::class, $followerStorage);

        $client->request('GET', '/@person/followers');
        self::assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();
        self::assertIsString($content);
        self::assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'type' => 'OrderedCollection',
            'totalItems' => 9,
            'first' => 'http://localhost/@person/followers?page=1',
            'id' => 'http://localhost/@person/followers',
        ], json_decode($content, true, 512, JSON_THROW_ON_ERROR));
    }

    public function testFirstPage(): void
    {
        $client = self::createClient();
        $client->catchExceptions(false);

        $followerStorage = $this->createMock(FollowerStorageInterface::class);
        $followerStorage->expects(self::once())->method('count')->willReturn(4);
        $followerStorage->expects(self::once())->method('list')->willReturn([
            Uri::fromString('http://localhost/@alpha'),
            Uri::fromString('http://localhost/@beta'),
            Uri::fromString('http://localhost/@gamma'),
            Uri::fromString('http://localhost/@delta'),
        ]);
        self::getContainer()->set(FollowerStorageInterface::class, $followerStorage);

        $client->request('GET', '/@person/followers?page=1');
        self::assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();
        self::assertIsString($content);
        self::assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'type' => 'OrderedCollectionPage',
            'totalItems' => 4,
            'partOf' => 'http://localhost/@person/followers',
            'orderedItems' => [
                'http://localhost/@alpha',
                'http://localhost/@beta',
                'http://localhost/@gamma',
                'http://localhost/@delta',
            ],
            'id' => 'http://localhost/@person/followers?page=1',
        ], json_decode($content, true, 512, JSON_THROW_ON_ERROR));
    }
}
