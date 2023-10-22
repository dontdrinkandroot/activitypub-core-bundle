<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;

class ExtendedObject extends CoreObject
{
    public function __construct(private readonly ObjectType $type)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return $this->type->value;
    }
}
