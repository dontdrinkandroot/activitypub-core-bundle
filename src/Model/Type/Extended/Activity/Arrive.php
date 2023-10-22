<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\IntransitiveActivity;

class Arrive extends IntransitiveActivity
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return IntransitiveActivityType::ARRIVE->value;
    }
}
