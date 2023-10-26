<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

interface LocalActorServiceInterface
{
    public function findLocalActorByUsername(string $username): ?LocalActorInterface;

    public function findLocalActorByUri(Uri $uri): ?LocalActorInterface;

    /**
     * TODO: Maybe deprecate and move to object provider
     * @param LocalActorInterface $localActor
     * @return Actor
     */
    public function toActivityPubActor(LocalActorInterface $localActor): Actor;

    public function getSignKey(LocalActorInterface $localActor): SignKey;
}
