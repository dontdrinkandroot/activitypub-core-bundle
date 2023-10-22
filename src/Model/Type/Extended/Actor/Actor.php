<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\PublicKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

// TODO: Complete
class Actor extends CoreObject
{
    public function __construct(private readonly ActorType $actorType)
    {
    }

    public ?Uri $inbox = null;

    public ?Uri $outbox = null;

    public ?Uri $following = null;

    public ?Uri $followers = null;

    public ?Uri $liked = null;

    public ?string $preferredUsername = null;

    public ?PublicKey $publicKey = null;

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return $this->actorType->value;
    }
}
