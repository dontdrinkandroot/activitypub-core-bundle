<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Unit\Service\Signature;

use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\ActorResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureVerifier;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class SignatureVerifierTest extends TestCase
{
    public function testVerify(): void
    {
        $this->markTestSkipped('Date is outdated and digest is not calculated correctly');

        $actorService = $this->createMock(ActorResolverInterface::class);
        $publicKeyPem = <<<KEY
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA2p5g2QzhaSoEcNMY866a
JLS8hM0+YqmW0Tu3HkToP19sI07fmzvKtE6kTJC2yXyKtH1n4NNUEuExgSAQL6qy
2OdciCSecs+yMwajo5G5VvfZ9UqdRyZPwJGh+Swu2ycZZcABKS7N1t6HurVqH0vd
af/gNwSDSyMVsIHlN5fqxaaHfhvusHGdxCbtzLidnmcEUbwF9U3+0hisXGG1PT5F
F3t6rPnSPh121oT3T+OMuaPfN4r949sVgzoeL1IFUp2rMisIVgyiwKUsfDpUON/K
d8uEZffY4FAsSnt7xUw+SOKVheNhL2CVBz9rT8vVPGe8bNImXuGfT3qf+G1Sc4sD
YwIDAQAB
-----END PUBLIC KEY----- 
KEY;
        $actorService->method('getPublicKey')->willReturn($publicKeyPem);
        $signatureService = new SignatureVerifier($actorService);

        $body = <<<JSON
{
    "@context": "https://www.w3.org/ns/activitystreams",
    "id": "https://mastodon.localdomain/9281592c-324d-44a1-a74e-97beb38e3986",
    "type": "Follow",
    "actor": "https://mastodon.localdomain/users/test",
    "object": "https://app.localdomain/@person/"
}
JSON;

        $request = Request::create(
            uri: 'https://app.localdomain/@person/inbox/',
            method: 'POST',
            server: [
                'HTTP_HOST' => 'app.localdomain',
                'HTTP_DATE' => 'Sun, 01 Oct 2023 12:54:12 GMT',
                'HTTP_DIGEST' => 'SHA-256=ho66aljXuWoocw01ZD5H6vTTZ+0crHb8rUURC2z9vJg=',
                'CONTENT_TYPE' => 'application/activity+json',
                'HTTP_SIGNATURE' => 'keyId="https://mastodon.localdomain/users/test#main-key",algorithm="rsa-sha256",headers="(request-target) host date digest content-type",signature="DNmRpPf7qSw8eYUt5R/nn18UW2Hg+g77RWHeA7AYpaOKIv6/taMgdpevP/7WMJnP2bcEwHbzc9b2Iltgp2eze6t7R60uYtiHh1+7O9vvaF/4MzQ1x/A2vzXCW6J2VtWfD3sjY12mfq+dYVkSi2CAcgMBG3DeCkud1Vjkk9vLq1r8KcxcHR3JBATrog0ZHCDvmIsmY0x2NbutFBH6yXxRF5Dx6fLqKiy2ZyyhwdpfAtLjI9iNYlDJpwmksYsula+KGHURLlnuQo+8ZvNDl5uIR90sPhFMB0bFY8nhQXFDLcid+NTGZJDsTrAlV9JCfbMQiIf6qjbyBveWQ8slUIq2Aw=="',
            ],
            content: $body
        );
        self::assertTrue($signatureService->verifyRequest($request));
    }
}
