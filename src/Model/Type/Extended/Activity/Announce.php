<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Activity;

class Announce extends Activity
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return ActivityType::ANNOUNCE->value;
    }
}
