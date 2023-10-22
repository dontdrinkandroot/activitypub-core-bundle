<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model;

class KeyPair
{
    public function __construct(
        public readonly string $privateKey,
        public readonly string $publicKey
    ) {
    }
}
