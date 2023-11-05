<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Direction;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowResponseMode;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowResponseType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowState;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Accept;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Follow;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Reject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Undo;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObjectsCollection;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorUriGeneratorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Delivery\DeliveryServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use RuntimeException;

class FollowService implements FollowServiceInterface
{
    public function __construct(
        private readonly FollowStorageInterface $followStorage,
        private readonly DeliveryServiceInterface $deliveryService,
        private readonly ObjectResolverInterface $objectResolver,
        private readonly LocalActorUriGeneratorInterface $localActorUriGenerator,
        private FollowResponseMode $followResponseMode
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function follow(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $this->followStorage->add($localActor, $remoteActorId, Direction::OUTGOING);

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
        $this->followStorage->remove($localActor, $remoteActorId, Direction::OUTGOING);

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
        $this->followStorage->accept($localActor, $remoteActorId, Direction::INCOMING);

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
        $this->followStorage->reject($localActor, $remoteActorId, Direction::INCOMING);

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
    public function findFollowingState(LocalActorInterface $localActor, Uri $remoteActorId): ?FollowState
    {
        return $this->followStorage->findState($localActor, $remoteActorId, Direction::OUTGOING);
    }

    /**
     * {@inheritdoc}
     */
    public function findFollowerState(LocalActorInterface $localActor, Uri $remoteActorId): ?FollowState
    {
        return $this->followStorage->findState($localActor, $remoteActorId, Direction::INCOMING);
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
        return $this->followStorage->list($localActor, Direction::INCOMING, $followState, $offset, $itemsPerPage);
    }

    /**
     * {@inheritdoc}
     */
    public function getNumFollowers(
        LocalActorInterface $localActor,
        FollowState $followState = FollowState::ACCEPTED
    ): int {
        return $this->followStorage->count($localActor, Direction::INCOMING, $followState);
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
        return $this->followStorage->list($localActor, Direction::OUTGOING, $followState, $offset, $itemsPerPage);
    }

    /**
     * {@inheritdoc}
     */
    public function getNumFollowing(
        LocalActorInterface $localActor,
        FollowState $followState = FollowState::ACCEPTED
    ): int {
        return $this->followStorage->count($localActor, Direction::OUTGOING, $followState);
    }

    /**
     * {@inheritdoc}
     */
    public function onFollowerRequest(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $this->followStorage->add($localActor, $remoteActorId, Direction::INCOMING);

        match ($this->followResponseMode) {
            FollowResponseMode::ACCEPT => $this->acceptFollower($localActor, $remoteActorId),
            FollowResponseMode::REJECT => $this->rejectFollower($localActor, $remoteActorId),
            FollowResponseMode::MANUAL => null,
        };
    }

    /**
     * {@inheritdoc}
     */
    public function onFollowingResponse(
        LocalActorInterface $localActor,
        Uri $remoteActorId,
        FollowResponseType $responseType
    ): void {
        match ($responseType) {
            FollowResponseType::ACCEPTED => $this->followStorage->accept(
                $localActor,
                $remoteActorId,
                Direction::OUTGOING
            ),
            FollowResponseType::REJECTED => $this->followStorage->reject(
                $localActor,
                $remoteActorId,
                Direction::OUTGOING
            ),
        };
    }

    private function getInbox(Uri $actorId): Uri
    {
        return $this->objectResolver->resolveTyped($actorId, Actor::class)?->inbox
            ?? throw new RuntimeException('Inbox not found');
    }

    public function setFollowResponseMode(FollowResponseMode $followResponseMode): void
    {
        $this->followResponseMode = $followResponseMode;
    }
}
