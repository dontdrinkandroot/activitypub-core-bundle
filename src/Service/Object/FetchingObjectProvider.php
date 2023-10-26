<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;

class FetchingObjectProvider implements ObjectProviderInterface
{
    public function __construct(
        private readonly ActivityPubClientInterface $client,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function provide(Uri $uri, ?SignKey $signKey): CoreType|false|null
    {
        return $this->client->request(method: 'GET', uri: $uri, signKey: $signKey);
    }
}
