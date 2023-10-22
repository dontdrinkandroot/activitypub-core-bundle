<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\WebFinger;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

interface WebFingerServiceInterface
{
    public function resolveIri(string $username, string $domain): ?Uri;
}
