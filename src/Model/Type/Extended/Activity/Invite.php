<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity;

class Invite extends Offer
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return ActivityType::INVITE->value;
    }
}
