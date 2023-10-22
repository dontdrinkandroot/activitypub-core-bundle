<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

interface LocalObjectResolverInterface
{
    public function hasObject(Uri $uri): bool;
}
