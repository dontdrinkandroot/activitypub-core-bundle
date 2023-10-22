<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property;

class PublicKey
{
    public function __construct(
        public Uri $id,
        public Uri $owner,
        public string $publicKeyPem
    ) {
    }
}
