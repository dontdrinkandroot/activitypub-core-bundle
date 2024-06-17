<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\IntransitiveActivity;
use Override;

class Arrive extends IntransitiveActivity
{
    #[Override]
    public function getType(): string
    {
        return IntransitiveActivityType::ARRIVE->value;
    }
}
