<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

interface ObjectProviderInterface
{
    public function provide(Uri $uri, ?SignKey $signKey): CoreObject|false|null;
}
