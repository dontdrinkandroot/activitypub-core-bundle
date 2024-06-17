<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity;

use Override;

class Invite extends Offer
{
    #[Override]
    public function getType(): string
    {
        return ActivityType::INVITE->value;
    }
}
