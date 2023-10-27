<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectProviderInterface;

class MockObjectProvider implements ObjectProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function provide(Uri $uri, ?SignKey $signKey): CoreObject|false|null
    {
        return false;
    }
}
