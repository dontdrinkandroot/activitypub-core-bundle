<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowState;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Accept;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Follow;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Reject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Undo;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObjectsCollection;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\ActorResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorUriGeneratorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Delivery\DeliveryServiceInterface;
use RuntimeException;

class FollowService implements FollowServiceInterface
{
    public function __construct(
        private readonly FollowingStorageInterface $followingStorage,
        private readonly FollowerStorageInterface $followerStorage,
        private readonly DeliveryServiceInterface $deliveryService,
        private readonly ActorResolverInterface $actorService,
        private readonly LocalActorUriGeneratorInterface $localActorUriGenerator
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function follow(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $this->followingStorage->addRequest($localActor, $remoteActorId);

        $follow = new Follow();
        //TODO: Use Id
        $localActorId = $this->localActorUriGenerator->generateId($localActor->getUsername());
        $follow->actor = LinkableObjectsCollection::singleLinkFromUri($localActorId);
        $follow->object = LinkableObject::linkFromUri($remoteActorId);

        $this->deliveryService->send($localActor, $this->getInbox($remoteActorId), $follow);
    }

    /**
     * {@inheritdoc}
     */
    public function unfollow(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $this->followingStorage->remove($localActor, $remoteActorId);

        $undo = new Undo();
        //TODO: Use Id
        $localActorId = $this->localActorUriGenerator->generateId($localActor->getUsername());
        $undo->actor = LinkableObjectsCollection::singleLinkFromUri($localActorId);

        $follow = new Follow();
        $follow->actor = LinkableObjectsCollection::singleLinkFromUri($localActorId);
        $follow->object = LinkableObject::linkFromUri($remoteActorId);

        $undo->object = LinkableObject::fromObject($follow);

        $this->deliveryService->send($localActor, $this->getInbox($remoteActorId), $undo);
    }

    /**
     * {@inheritdoc}
     */
    public function acceptFollower(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $this->followerStorage->acceptRequest($localActor, $remoteActorId);

        $accept = new Accept();
        //TODO: Use Id
        $localActorId = $this->localActorUriGenerator->generateId($localActor->getUsername());
        $accept->actor = LinkableObjectsCollection::singleLinkFromUri($localActorId);

        $follow = new Follow();
        $follow->actor = LinkableObjectsCollection::singleLinkFromUri($remoteActorId);
        $follow->object = LinkableObject::linkFromUri($localActorId);

        $accept->object = LinkableObject::fromObject($follow);

        $this->deliveryService->send($localActor, $this->getInbox($remoteActorId), $accept);
    }

    /**
     * {@inheritdoc}
     */
    public function rejectFollower(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $this->followerStorage->rejectRequest($localActor, $remoteActorId);

        $reject = new Reject();
        //TODO: Use Id
        $localActorId = $this->localActorUriGenerator->generateId($localActor->getUsername());
        $reject->actor = LinkableObjectsCollection::singleLinkFromUri($localActorId);

        $follow = new Follow();
        $follow->actor = LinkableObjectsCollection::singleLinkFromUri($remoteActorId);
        $follow->object = LinkableObject::linkFromUri($localActorId);

        $reject->object = LinkableObject::fromObject($follow);

        $this->deliveryService->send($localActor, $this->getInbox($remoteActorId), $reject);
    }

    /**
     * {@inheritdoc}
     */
    public function listFollowers(
        LocalActorInterface $localActor,
        FollowState $followState = FollowState::ACCEPTED,
        int $page = 1,
        int $itemsPerPage = 50
    ): array {
        $offset = ($page - 1) * $itemsPerPage;
        return $this->followerStorage->list($localActor, $followState, $offset, $itemsPerPage);
    }

    /**
     * {@inheritdoc}
     */
    public function findFollowingState(LocalActorInterface $localActor, Uri $remoteActorId): ?FollowState
    {
        return $this->followingStorage->findState($localActor, $remoteActorId);
    }

    /**
     * {@inheritdoc}
     */
    public function getNumFollowers(
        LocalActorInterface $localActor,
        FollowState $followState = FollowState::ACCEPTED
    ): int
    {
        return $this->followerStorage->count($localActor, $followState);
    }

    /**
     * {@inheritdoc}
     */
    public function listFollowing(
        LocalActorInterface $localActor,
        FollowState $followState = FollowState::ACCEPTED,
        int $page = 1,
        int $itemsPerPage = 50
    ): array {
        $offset = ($page - 1) * $itemsPerPage;
        return $this->followingStorage->list($localActor, $followState, $offset, $itemsPerPage);
    }

    /**
     * {@inheritdoc}
     */
    public function getNumFollowing(
        LocalActorInterface $localActor,
        FollowState $followState = FollowState::ACCEPTED
    ): int {
        return $this->followingStorage->count($localActor, $followState);
    }

    private function getInbox(Uri $actorId): Uri
    {
        return $this->actorService->resolveInbox($actorId)
            ?? throw new RuntimeException('Inbox not found');
    }
}
