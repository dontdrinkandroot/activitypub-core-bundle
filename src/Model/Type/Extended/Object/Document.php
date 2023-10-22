<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;

class Document extends CoreObject
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return ObjectType::DOCUMENT->value;
    }
}
