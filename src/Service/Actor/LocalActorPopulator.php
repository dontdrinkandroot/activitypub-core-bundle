<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\ActorType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\JsonLdContext;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Endpoints;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\TypeClassRegistry;

/**
 * Populates an Actor with the default values of a LocalActor.
 */
class LocalActorPopulator
{
    public function __construct(
        private readonly LocalActorUriGenerator $localActorUriGenerator,
        private readonly TypeClassRegistry $typeClassRegistry
    ) {
    }

    public function populate(LocalActorInterface $localActor, Actor|ActorType $actorOrType): Actor
    {
        $actor = ($actorOrType instanceof Actor)
            ? $actorOrType
            : $this->typeClassRegistry->actorFromType($actorOrType);

        $actor->preferredUsername = $localActor->getUsername();

        if (null === $actor->jsonLdContext) {
            $actor->jsonLdContext = new JsonLdContext([]);
        }
        $actor->jsonLdContext->add('https://w3id.org/security/v1');

        $actor->id = $this->localActorUriGenerator->generateId($localActor);
        $actor->inbox = $this->localActorUriGenerator->generateInbox($localActor);
        $actor->outbox = $this->localActorUriGenerator->generateOutbox($localActor);
        $actor->following = $this->localActorUriGenerator->generateFollowing($localActor);
        $actor->followers = $this->localActorUriGenerator->generateFollowers($localActor);

        if (null === $actor->endpoints) {
            $actor->endpoints = new Endpoints();
        }
        $actor->endpoints['sharedInbox'] = $this->localActorUriGenerator->generateSharedInbox();

        return $actor;
    }
}
