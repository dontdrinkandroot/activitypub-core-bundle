<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

use Override;

class Page extends Document
{
    #[Override]
    public function getType(): string
    {
        return ObjectType::PAGE->value;
    }
}
