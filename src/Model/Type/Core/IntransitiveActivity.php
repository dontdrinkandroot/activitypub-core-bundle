<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\IntransitiveActivityType;

class IntransitiveActivity extends AbstractActivity
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return IntransitiveActivityType::INTRANSITIVE_ACTIVITY->value;
    }
}
