<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Override;

class Profile extends CoreObject
{
    public ?CoreObject $describes = null;

    #[Override]
    public function getType(): string
    {
        return ObjectType::PROFILE->value;
    }
}
