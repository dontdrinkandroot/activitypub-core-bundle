<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\KeyPair;
use Override;
use phpseclib3\Crypt\RSA;

class KeyPairGenerator implements KeyPairGeneratorInterface
{
    #[Override]
    public function generateKeyPair(): KeyPair
    {
        $privateKey = RSA::createKey();
        $publicKey = $privateKey->getPublicKey();

        return new KeyPair(
            privateKey: $privateKey->toString('PKCS1'),
            publicKey: $publicKey->toString('PKCS8')
        );
    }
}
