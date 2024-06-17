<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\ObjectType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObjectsCollection;
use Override;

class OrderedCollection extends AbstractCollection
{
    public ?LinkableObjectsCollection $orderedItems = null;

    #[Override]
    public function getType(): string
    {
        return ObjectType::ORDERED_COLLECTION->value;
    }
}
