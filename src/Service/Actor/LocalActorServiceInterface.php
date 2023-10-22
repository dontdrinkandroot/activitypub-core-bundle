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

    public function toActivityPubActor(LocalActorInterface $localActor): Actor;

    public function getSignKey(LocalActorInterface $localActor): SignKey;
}
