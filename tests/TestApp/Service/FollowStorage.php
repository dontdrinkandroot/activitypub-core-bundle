<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Service;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Direction;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowState;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowStorageInterface;
use Override;
use RuntimeException;

class FollowStorage implements FollowStorageInterface
{
    #[Override]
    public function add(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): void
    {
        // TODO: Implement add() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }

    #[Override]
    public function accept(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): void
    {
        // TODO: Implement accept() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }

    #[Override]
    public function reject(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): void
    {
        // TODO: Implement reject() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }

    #[Override]
    public function remove(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): void
    {
        // TODO: Implement remove() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }

    #[Override]
    public function findState(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): ?FollowState
    {
        // TODO: Implement findState() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }

    #[Override]
    public function list(
        LocalActorInterface $localActor,
        Direction $direction,
        FollowState $followState = FollowState::ACCEPTED,
        int $offset = 0,
        int $limit = 50
    ): array {
        // TODO: Implement list() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }

    #[Override]
    public function count(
        LocalActorInterface $localActor,
        Direction $direction,
        FollowState $followState = FollowState::ACCEPTED
    ): int {
        // TODO: Implement count() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }
}
