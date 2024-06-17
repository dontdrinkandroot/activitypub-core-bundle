<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Activity;
use Override;

class TentativeReject extends Activity
{
    #[Override]
    public function getType(): string
    {
        return ActivityType::TENTATIVE_REJECT->value;
    }
}
