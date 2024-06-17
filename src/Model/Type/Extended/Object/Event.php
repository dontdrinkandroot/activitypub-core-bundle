<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Override;

class Event extends CoreObject
{
    #[Override]
    public function getType(): string
    {
        return ObjectType::EVENT->value;
    }
}
