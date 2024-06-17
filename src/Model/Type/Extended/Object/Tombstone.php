<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

use DateTimeInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Override;

class Tombstone extends CoreObject
{
    public ?string $formerType = null;

    public ?DateTimeInterface $deleted = null;

    #[Override]
    public function getType(): string
    {
        return ObjectType::TOMBSTONE->value;
    }
}
