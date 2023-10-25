<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance;

use Dontdrinkandroot\ActivityPubCoreBundle\Tests\WebTestCase;

class WebfingerActionTest extends WebTestCase
{
    public function testWebfinger(): void
    {
        $client = static::createClient();

        $client->request('GET', '/.well-known/webfinger?resource=acct:person@localhost');
        $response = $client->getResponse();
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('application/jrd+json', $response->headers->get('Content-Type'));
        $json = $response->getContent();
        self::assertIsString($json);
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        self::assertEquals([
            'subject' => 'acct:person@localhost',
            'links' => [
                [
                    'rel' => 'self',
                    'type' => 'application/activity+json',
                    'href' => 'https://localhost/@person',
                ]
            ]
        ], $data);
    }
}
