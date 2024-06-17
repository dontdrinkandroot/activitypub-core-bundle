<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\IntransitiveActivity;
use Override;

class Travel extends IntransitiveActivity
{
    #[Override]
    public function getType(): string
    {
        return IntransitiveActivityType::TRAVEL->value;
    }
}
