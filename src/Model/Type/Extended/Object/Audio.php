<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

use Override;

class Audio extends Document
{
    #[Override]
    public function getType(): string
    {
        return ObjectType::AUDIO->value;
    }
}
