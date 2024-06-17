<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Endpoints;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\PublicKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Override;

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

    // TODO: This mapping may be nested inside the actor document as the value or may be a link to a JSON-LD document with these properties.
    public ?Endpoints $endpoints = null;

    #[Override]
    public function getType(): string
    {
        return $this->actorType->value;
    }
}
