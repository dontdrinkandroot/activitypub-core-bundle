<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Override;

class ExtendedObject extends CoreObject
{
    public function __construct(private readonly ObjectType $type)
    {
    }

    #[Override]
    public function getType(): string
    {
        return $this->type->value;
    }
}
