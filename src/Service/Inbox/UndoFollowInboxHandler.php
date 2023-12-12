<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Direction;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\AbstractActivity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Follow;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Undo;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowStorageInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Symfony\Component\HttpFoundation\Response;

class UndoFollowInboxHandler implements InboxHandlerInterface
{
    public function __construct(
        private readonly FollowStorageInterface $followerStorage,
        private readonly ObjectResolverInterface $objectResolver,
        private readonly LocalActorServiceInterface $localActorService,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function handle(
        AbstractActivity $activity,
        Actor $signActor,
        ?LocalActorInterface $inboxActor = null
    ): ?Response {
        if (
            !($activity instanceof Undo)
            || (null === $activity->object)
            || (null === ($undoActorId = $activity->actor?->getSingleValueId()))
        ) {
            return null;
        }

        if (!$undoActorId->equals($signActor->getId())) {
            return new Response(status: Response::HTTP_FORBIDDEN, headers: [
                'Content-Type' => 'application/activity+json'
            ]);
        }

        $follow = $this->objectResolver->resolve($activity->object);
        if (
            !($follow instanceof Follow)
            || (null === ($followActorId = $follow->actor?->getSingleValueId()))
            || (null === ($followObjectId = $follow->object?->getId()))
        ) {
            return null;
        }

        if (!$followActorId->equals($signActor->getId())) {
            return new Response(status: Response::HTTP_FORBIDDEN, headers: [
                'Content-Type' => 'application/activity+json'
            ]);
        }

        if (null === ($localActor = $this->localActorService->findLocalActorByUri($followObjectId))) {
            return new Response(status: Response::HTTP_NOT_FOUND, headers: [
                'Content-Type' => 'application/activity+json'
            ]);
        }

        $this->followerStorage->remove($localActor, $followActorId, Direction::INCOMING);

        return new Response(status: Response::HTTP_ACCEPTED, headers: [
            'Content-Type' => 'application/activity+json'
        ]);
    }
}
