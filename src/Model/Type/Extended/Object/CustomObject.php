<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Override;

class CustomObject extends CoreObject
{
    public function __construct(private readonly string $type)
    {
    }

    #[Override]
    public function getType(): string
    {
        return $this->type;
    }
}
