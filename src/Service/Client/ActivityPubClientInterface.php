<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Client;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

interface ActivityPubClientInterface
{
    /**
     * {@inheritdoc}
     */
    public function request(
        string $method,
        Uri $uri,
        CoreType|string|null $content = null,
        SignKey $signKey = null
    ): ?CoreObject;
}
