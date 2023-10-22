<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

class SignKey
{
    public function __construct(
        public Uri $id,
        public Uri $owner,
        public string $privateKeyPem,
        public string $publicKeyPem
    ) {
    }
}
