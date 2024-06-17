<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Override;

class Document extends CoreObject
{
    #[Override]
    public function getType(): string
    {
        return ObjectType::DOCUMENT->value;
    }
}
