<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectProviderInterface;
use Override;

class MockObjectProvider implements ObjectProviderInterface
{
    #[Override]
    public function provide(Uri $uri, ?SignKey $signKey): CoreObject|false|null
    {
        return false;
    }
}
