<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

use Override;

class Image extends Document
{
    #[Override]
    public function getType(): string
    {
        return ObjectType::IMAGE->value;
    }
}
