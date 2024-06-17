<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Link;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Link;
use Override;

class Mention extends Link
{
    public const string TYPE = 'Mention';

    #[Override]
    public function getType(): string
    {
        return self::TYPE;
    }
}
