<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Override;

class FetchingObjectProvider implements ObjectProviderInterface
{
    public function __construct(
        private readonly ActivityPubClientInterface $client,
    ) {
    }

    #[Override]
    public function provide(Uri $uri, ?SignKey $signKey): CoreObject|false|null
    {
        return $this->client->request(method: 'GET', uri: $uri, signKey: $signKey);
    }
}
