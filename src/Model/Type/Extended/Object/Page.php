<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

class Page extends Document
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return ObjectType::PAGE->value;
    }
}
