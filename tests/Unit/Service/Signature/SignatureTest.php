<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Unit\Service\Signature;

use DateTime;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Header;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Person;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\PublicKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\ActorResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\KeyPairGenerator;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureGenerator;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureTools;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureVerifier;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class SignatureTest extends TestCase
{
    public function testCreateSignedRequestHeadersAndVerify(): void
    {
        $keyPair = (new KeyPairGenerator())->generateKeyPair();

        $body = <<<JSON
{
    "@context": "https://www.w3.org/ns/activitystreams",
    "id": "https://mastodon.localdomain/0d26e232-b801-4795-a32f-bca4f2908fbf",
    "type": "Follow",
    "actor": "https://mastodon.localdomain/users/test",
    "object": "https://app.localdomain/@person/"
}
JSON;

        $signActorId = Uri::fromString('https://mastodon.localdomain/users/test');
        $signKey = new SignKey(
            id: Uri::fromString('https://mastodon.localdomain/users/test#main-key'),
            owner: $signActorId,
            privateKeyPem: $keyPair->privateKey,
            publicKeyPem: $keyPair->publicKey
        );

        $signActor = new Person();
        $signActor->id = $signActorId;
        $signActor->publicKey = new PublicKey(
            id: $signKey->id,
            owner: $signKey->owner,
            publicKeyPem: $signKey->publicKeyPem
        );

        $headers = [
            Header::HOST => 'mastodon.localdomain',
            Header::ACCEPT => 'application/ld+json; profile="https://www.w3.org/ns/activitystreams"',
            Header::DATE => (new DateTime())->format(DateTime::RFC7231),
            Header::CONTENT_TYPE => 'application/ld+json; profile="https://www.w3.org/ns/activitystreams"',
            Header::DIGEST => SignatureTools::createDigestHeaderValue($body)
        ];

        $signatureGenerator = new SignatureGenerator();
        $headers[Header::SIGNATURE] = $signatureGenerator->generateSignatureHeader(
            method: 'POST',
            path: '/users/test/inbox',
            key: $signKey,
            headers: $headers
        );

        $this->assertArrayHasKey('Signature', $headers);
        $this->assertArrayHasKey('Date', $headers);
        $this->assertArrayHasKey('Host', $headers);
        $this->assertArrayHasKey('Digest', $headers);
        $this->assertArrayHasKey('Content-Type', $headers);

        $actorService = $this->createMock(ActorResolverInterface::class);
        $actorService->method('resolve')->willReturn($signActor);
        $signatureVerifier = new SignatureVerifier($actorService);

        $request = Request::create(
            uri: 'https://mastodon.localdomain/users/test/inbox',
            method: 'POST',
            server: [
                'HTTP_HOST' => 'mastodon.localdomain',
                'HTTP_ACCEPT' => 'application/ld+json; profile="https://www.w3.org/ns/activitystreams"',
                'HTTP_SIGNATURE' => $headers['Signature'],
                'HTTP_DATE' => $headers['Date'],
                'HTTP_DIGEST' => $headers['Digest'],
                'CONTENT_TYPE' => $headers['Content-Type'],
            ],
            content: $body
        );
        $signatureVerifier->verifyRequest($request);
    }
}