<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowState;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

interface FollowerStorageInterface
{
    public function addRequest(LocalActorInterface $localActor, Uri $remoteActorId): void;

    public function acceptRequest(LocalActorInterface $localActor, Uri $remoteActorId): void;

    public function rejectRequest(LocalActorInterface $localActor, Uri $remoteActorId): void;

    public function remove(LocalActorInterface $localActor, Uri $remoteActorId): void;

    /**
     * @return Uri[]
     */
    public function list(
        LocalActorInterface $localActor,
        FollowState $followState = FollowState::ACCEPTED,
        int $offset = 0,
        int $limit = 50
    ): array;

    public function count(LocalActorInterface $localActor): int;
}
