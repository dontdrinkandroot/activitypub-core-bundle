<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity;

use Override;

class Block extends Ignore
{
    #[Override]
    public function getType(): string
    {
        return ActivityType::BLOCK->value;
    }
}
