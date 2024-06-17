<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\IntransitiveActivityType;
use Override;

class IntransitiveActivity extends AbstractActivity
{
    #[Override]
    public function getType(): string
    {
        return IntransitiveActivityType::INTRANSITIVE_ACTIVITY->value;
    }
}
