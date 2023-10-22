<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance;

use Dontdrinkandroot\ActivityPubCoreBundle\Tests\WebTestCase;

class ActorActionTest extends WebTestCase
{
    public function testNotFound(): void
    {
        $client = self::createClient();

        $client->request('GET', '/@unknown');
        self::assertResponseStatusCodeSame(404);
    }

    public function testExisting(): void
    {
        $client = self::createClient();

        $client->request('GET', '/@person');
        self::assertResponseIsSuccessful();
    }

    public function testInvalidAccept(): void
    {
        $client = self::createClient();

        $client->request('GET', '/@person', [], [], ['HTTP_ACCEPT' => 'text/plain']);
        self::assertResponseStatusCodeSame(406);
    }

    public function testExistingJsonAccept(): void
    {
        $client = self::createClient();
        $client->catchExceptions(false);

        $client->request('GET', '/@person', [], [], ['HTTP_ACCEPT' => 'application/activity+json']);
        self::assertResponseIsSuccessful();
        $response = $client->getResponse();
        self::assertStringStartsWith(
            'application/activity+json',
            $response->headers->get('Content-Type') ?? ''
        );

        $client->request(
            'GET',
            '/@person',
            [],
            [],
            ['HTTP_ACCEPT' => 'application/ld+json; profile="https://www.w3.org/ns/activitystreams']
        );
        self::assertResponseIsSuccessful();
        $response = $client->getResponse();
        self::assertStringStartsWith(
            'application/activity+json',
            $response->headers->get('Content-Type') ?? ''
        );
    }
}
