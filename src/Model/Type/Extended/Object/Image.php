<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

class Image extends Document
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return ObjectType::IMAGE->value;
    }
}
