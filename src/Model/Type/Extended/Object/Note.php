<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Override;

class Note extends CoreObject
{
    #[Override]
    public function getType(): string
    {
        return ObjectType::NOTE->value;
    }
}
