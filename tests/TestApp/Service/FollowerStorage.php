<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Service;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowState;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowerStorageInterface;
use RuntimeException;

class FollowerStorage implements FollowerStorageInterface
{
    /**
     * {@inheritdoc}
     */
    public function addRequest(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        // TODO: Implement addRequest() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function acceptRequest(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        // TODO: Implement acceptRequest() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function rejectRequest(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        // TODO: Implement rejectRequest() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function remove(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        // TODO: Implement remove() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function list(
        LocalActorInterface $localActor,
        FollowState $followState = FollowState::ACCEPTED,
        int $offset = 0,
        int $limit = 50
    ): array {
        // TODO: Implement list() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function count(LocalActorInterface $localActor): int
    {
        // TODO: Implement count() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }
}