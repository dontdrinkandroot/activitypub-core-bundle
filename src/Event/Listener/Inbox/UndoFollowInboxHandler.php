<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Event\Listener\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Event\InboxEvent;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Direction;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Follow;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Undo;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowStorageInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Symfony\Component\HttpFoundation\Response;

class UndoFollowInboxHandler
{
    public function __construct(
        private readonly FollowStorageInterface $followerStorage,
        private readonly ObjectResolverInterface $objectResolver,
        private readonly LocalActorServiceInterface $localActorService,
    ) {
    }

    public function __invoke(InboxEvent $event): void
    {
        $activity = $event->activity;
        if (
            !($activity instanceof Undo)
            || (null === $activity->object)
            || (null === ($undoActorId = $activity->actor?->getSingleValueId()))
        ) {
            return;
        }

        $signActor = $event->verify();
        if (!$undoActorId->equals($signActor->getId())) {
            $event->setResponse(new Response(status: Response::HTTP_FORBIDDEN, headers: [
                'Content-Type' => 'application/activity+json'
            ]));
            return;
        }

        $follow = $this->objectResolver->resolve($activity->object);
        if (
            !($follow instanceof Follow)
            || (null === ($followActorId = $follow->actor?->getSingleValueId()))
            || (null === ($followObjectId = $follow->object?->getId()))
        ) {
            return;
        }

        if (!$followActorId->equals($signActor->getId())) {
            $event->setResponse(new Response(status: Response::HTTP_FORBIDDEN, headers: [
                'Content-Type' => 'application/activity+json'
            ]));
            return;
        }

        if (null === ($localActor = $this->localActorService->findLocalActorByUri($followObjectId))) {
            $event->setResponse(new Response(status: Response::HTTP_NOT_FOUND, headers: [
                'Content-Type' => 'application/activity+json'
            ]));
            return;
        }

        $this->followerStorage->remove($localActor, $followActorId, Direction::INCOMING);

        $event->setResponse(new Response(status: Response::HTTP_ACCEPTED, headers: [
            'Content-Type' => 'application/activity+json'
        ]));
    }
}
