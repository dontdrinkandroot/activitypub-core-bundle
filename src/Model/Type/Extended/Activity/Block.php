<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity;

class Block extends Ignore
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return ActivityType::BLOCK->value;
    }
}
