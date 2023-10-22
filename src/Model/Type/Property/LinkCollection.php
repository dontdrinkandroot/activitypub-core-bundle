<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property;

use ArrayObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Link;

/**
 * @extends ArrayObject<array-key,Link>
 */
class LinkCollection extends ArrayObject
{
    public function isSingleValued(): bool
    {
        return 1 === $this->count();
    }
}
