<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\ObjectType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableCollection;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableCollectionPage;
use Override;

class OrderedCollectionPage extends OrderedCollection
{
    public ?LinkableCollection $partOf = null;

    public ?LinkableCollectionPage $next = null;

    public ?LinkableCollectionPage $prev = null;

    public ?int $startIndex = null; // TODO: xsd:nonNegativeInteger

    #[Override]
    public function getType(): string
    {
        return ObjectType::ORDERED_COLLECTION_PAGE->value;
    }
}
