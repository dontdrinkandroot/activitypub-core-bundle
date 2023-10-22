<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableCollectionPage;

abstract class AbstractCollection extends CoreObject
{
    public ?int $totalItems = null; // TODO: xsd:nonNegativeInteger

    public ?LinkableCollectionPage $current = null;

    public ?LinkableCollectionPage $first = null;

    public ?LinkableCollectionPage $last = null;
}
