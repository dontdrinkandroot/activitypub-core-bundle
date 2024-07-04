<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance\Controller\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Tests\WebTestCase;

class GetActionTest extends WebTestCase
{
    public function testMissingActor(): void
    {
        $client = static::createClient();

        $client->request('GET', '/@missing');
        $response = $client->getResponse();
        self::assertEquals(404, $response->getStatusCode());
    }

    public function testExisting(): void
    {
        $client = static::createClient();

        $client->request('GET', '/@person');
        $response = $client->getResponse();
        self::assertEquals(200, $response->getStatusCode());

        $json = $response->getContent();
        self::assertIsString($json);
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        self::assertNotNull($data['publicKey']['publicKeyPem'] ?? null);
        unset($data['publicKey']);

        self::assertEquals([
            '@context' => [
                'https://www.w3.org/ns/activitystreams',
                'https://w3id.org/security/v1'
            ],
            'type' => 'Person',
            'inbox' => 'https://localhost/@person/inbox',
            'outbox' => 'https://localhost/@person/outbox',
            'preferredUsername' => 'person',
            'id' => 'https://localhost/@person',
            'following' => 'https://localhost/@person/following',
            'followers' => 'https://localhost/@person/followers',
            'endpoints' => [
                'sharedInbox' => 'https://localhost/inbox'
            ]
        ], $data);
    }
}
