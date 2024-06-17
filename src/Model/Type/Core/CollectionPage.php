<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\ObjectType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableCollection;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableCollectionPage;
use Override;

class CollectionPage extends Collection
{
    public ?LinkableCollection $partOf = null;

    public ?LinkableCollectionPage $next = null;

    public ?LinkableCollectionPage $prev = null;

    #[Override]
    public function getType(): string
    {
        return ObjectType::COLLECTION_PAGE->value;
    }
}
