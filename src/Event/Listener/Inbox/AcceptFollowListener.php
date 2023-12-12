<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Event\Listener\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Event\InboxEvent;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowResponseType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Accept;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Follow;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Symfony\Component\HttpFoundation\Response;

class AcceptFollowListener
{
    public function __construct(
        private readonly FollowServiceInterface $followService,
        private readonly ObjectResolverInterface $objectResolver,
        private readonly LocalActorServiceInterface $localActorService
    ) {
    }

    public function __invoke(InboxEvent $event): void
    {
        $activity = $event->activity;

        if (
            !($activity instanceof Accept)
            || (null === $activity->object)
            || (null === ($acceptActorId = $activity->actor?->getSingleValueId()))
        ) {
            return;
        }

        $signActor = $event->verify();
        $inboxActor = $event->inboxOwner;

        if (!$acceptActorId->equals($signActor->getId())) {
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

        if (!$followObjectId->equals($signActor->getId())) {
            $event->setResponse(new Response(status: Response::HTTP_FORBIDDEN, headers: [
                'Content-Type' => 'application/activity+json'
            ]));

            return;
        }

        if (null === ($followActor = $this->localActorService->findLocalActorByUri($followActorId))) {
            $event->setResponse(new Response(status: Response::HTTP_NOT_FOUND, headers: [
                'Content-Type' => 'application/activity+json'
            ]));

            return;
        }

        $this->followService->onFollowingResponse($followActor, $signActor->getId(), FollowResponseType::ACCEPTED);

        $event->setResponse(new Response(status: Response::HTTP_OK, headers: [
            'Content-Type' => 'application/activity+json'
        ]));
    }
}
