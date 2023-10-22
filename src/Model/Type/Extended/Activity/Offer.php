<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Activity;

class Offer extends Activity
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return ActivityType::OFFER->value;
    }
}
