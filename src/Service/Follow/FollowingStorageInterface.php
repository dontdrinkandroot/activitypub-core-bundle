<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowState;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

interface FollowingStorageInterface
{
    public function addRequest(LocalActorInterface $localActor, Uri $remoteActorId): void;

    public function requestAccepted(LocalActorInterface $localActor, Uri $remoteActorId): void;

    public function requestRejected(LocalActorInterface $localActor, Uri $remoteActorId): void;

    public function remove(LocalActorInterface $localActor, Uri $remoteActorId): void;

    public function findState(LocalActorInterface $localActor, Uri $remoteActorId): ?FollowState;
}
