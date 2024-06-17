<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;
use Override;

class Relationship extends CoreObject
{
    public ?LinkableObject $subject = null;

    public ?LinkableObject $object = null;

    public ?LinkableObject $relationship = null;

    #[Override]
    public function getType(): string
    {
        return ObjectType::RELATIONSHIP->value;
    }
}
