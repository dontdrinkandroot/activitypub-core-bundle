<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;

class Event extends CoreObject
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return ObjectType::EVENT->value;
    }
}
