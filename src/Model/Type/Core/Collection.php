<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\ObjectType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObjectsCollection;
use Override;

class Collection extends AbstractCollection
{
    public ?LinkableObjectsCollection $items = null;

    #[Override]
    public function getType(): string
    {
        return ObjectType::COLLECTION->value;
    }
}
