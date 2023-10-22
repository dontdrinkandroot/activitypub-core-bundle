<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Link;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Link;

class Mention extends Link
{
    public const TYPE = 'Mention';

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
