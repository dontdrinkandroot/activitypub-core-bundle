<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Unit\Service\Signature;

use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\KeyPairGenerator;
use PHPUnit\Framework\TestCase;

class KeyPairGeneratorTest extends TestCase
{
    public function testGenerateKeyPair(): void
    {
        $keyGenerator = new KeyPairGenerator();
        $keyPair = $keyGenerator->generateKeyPair();

        self::assertStringStartsWith('-----BEGIN RSA PRIVATE KEY-----', $keyPair->privateKey);
        self::assertStringEndsWith('-----END RSA PRIVATE KEY-----', $keyPair->privateKey);

        self::assertStringStartsWith('-----BEGIN PUBLIC KEY-----', $keyPair->publicKey);
        self::assertStringEndsWith('-----END PUBLIC KEY-----', $keyPair->publicKey);
    }
}
