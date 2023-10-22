<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\KeyPair;

interface KeyPairGeneratorInterface
{
    public function generateKeyPair(): KeyPair;
}
