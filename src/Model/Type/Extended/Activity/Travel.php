<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\IntransitiveActivity;

class Travel extends IntransitiveActivity
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return IntransitiveActivityType::TRAVEL->value;
    }
}
