<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;

class Profile extends CoreObject
{
    public ?CoreObject $describes = null;

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return ObjectType::PROFILE->value;
    }
}
