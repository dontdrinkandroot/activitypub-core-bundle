<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\ObjectType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObjectsCollection;

class OrderedCollection extends AbstractCollection
{
    public ?LinkableObjectsCollection $orderedItems = null;

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return ObjectType::ORDERED_COLLECTION->value;
    }
}
