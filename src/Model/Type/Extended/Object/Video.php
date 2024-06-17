<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

use Override;

class Video extends Document
{
    #[Override]
    public function getType(): string
    {
        return ObjectType::VIDEO->value;
    }
}
