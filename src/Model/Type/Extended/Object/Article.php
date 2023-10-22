<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;

class Article extends CoreObject
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return ObjectType::ARTICLE->value;
    }
}
