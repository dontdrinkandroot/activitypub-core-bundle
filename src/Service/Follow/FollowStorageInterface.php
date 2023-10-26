<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Direction;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowState;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

interface FollowStorageInterface
{
    public function add(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): void;

    public function accept(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): void;

    public function reject(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): void;

    public function remove(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): void;

    public function findState(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): ?FollowState;

    /**
     * @return Uri[]
     */
    public function list(
        LocalActorInterface $localActor,
        Direction $direction,
        FollowState $followState = FollowState::ACCEPTED,
        int $offset = 0,
        int $limit = 50
    ): array;

    public function count(
        LocalActorInterface $localActor,
        Direction $direction,
        FollowState $followState = FollowState::ACCEPTED
    ): int;
}
