<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowState;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

interface FollowServiceInterface
{
    public function follow(LocalActorInterface $localActor, Uri $remoteActorId): void;

    public function unfollow(LocalActorInterface $localActor, Uri $remoteActorId): void;

    public function acceptFollower(LocalActorInterface $localActor, Uri $remoteActorId): void;

    public function rejectFollower(LocalActorInterface $localActor, Uri $remoteActorId): void;

    public function findFollowingState(LocalActorInterface $localActor, Uri $remoteActorId): ?FollowState;

    /**
     * @return Uri[]
     */
    public function listFollowers(
        LocalActorInterface $localActor,
        FollowState $followState = FollowState::ACCEPTED,
        int $page = 1,
        int $itemsPerPage = 50
    ): array;

    public function getNumFollowers(
        LocalActorInterface $localActor,
        FollowState $followState = FollowState::ACCEPTED
    ): int;

    /**
     * @return Uri[]
     */
    public function listFollowing(
        LocalActorInterface $localActor,
        FollowState $followState = FollowState::ACCEPTED,
        int $page = 1,
        int $itemsPerPage = 50
    ): array;

    public function getNumFollowing(
        LocalActorInterface $localActor,
        FollowState $followState = FollowState::ACCEPTED
    ): int;
}
