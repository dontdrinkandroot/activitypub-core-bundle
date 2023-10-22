<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

class Video extends Document
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return ObjectType::VIDEO->value;
    }
}
