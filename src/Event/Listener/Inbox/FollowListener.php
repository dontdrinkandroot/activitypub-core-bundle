<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Event\Listener\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Event\InboxEvent;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Follow;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class FollowListener
{
    public function __construct(
        private readonly LocalActorServiceInterface $localActorService,
        private readonly FollowServiceInterface $followService
    ) {
    }

    public function __invoke(InboxEvent $event): void
    {
        $activity = $event->activity;
        if (
            (!$activity instanceof Follow)
            || (null === ($targetObject = $activity->object))
            || (null === ($remoteActorId = $activity->actor?->getSingleValueId()))
        ) {
            return;
        }

        $signActor = $event->verify();
        if (!$remoteActorId->equals($signActor->getId())) {
            $event->setResponse(new Response(status: Response::HTTP_FORBIDDEN, headers: [
                'Content-Type' => 'application/activity+json'
            ]));
            return;
        }

        if (null === ($targetLocalActor = $this->localActorService->findLocalActorByUri($targetObject->getId()))) {
            $event->setResponse(new Response(status: Response::HTTP_NOT_FOUND, headers: [
                'Content-Type' => 'application/activity+json'
            ]));
            return;
        }

        $this->followService->onFollowerRequest($targetLocalActor, $remoteActorId);

        $event->setResponse(new Response(status: Response::HTTP_ACCEPTED, headers: [
            'Content-Type' => 'application/activity+json'
        ]));
    }
}
