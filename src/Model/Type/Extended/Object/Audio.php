<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

class Audio extends Document
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return ObjectType::AUDIO->value;
    }
}
