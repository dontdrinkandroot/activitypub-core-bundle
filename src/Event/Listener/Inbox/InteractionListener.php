<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Event\Listener\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Event\InboxEvent;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Announce;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Dislike;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Like;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Share\InteractionServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class InteractionListener
{
    public function __construct(
        private readonly ObjectResolverInterface $objectResolver,
        private readonly InteractionServiceInterface $interactionService,
        private readonly LocalActorServiceInterface $localActorService
    ) {
    }

    public function __invoke(InboxEvent $event): void
    {
        $activity = $event->activity;
        if (
            !(($activity instanceof Announce) || ($activity instanceof Like) || ($activity instanceof Dislike))
            || null === ($object = $activity->object)
            || null === ($activityActorId = $activity->actor?->getSingleValueId())
        ) {
            return;
        }

        $signActor = $event->verify();
        if (!$activityActorId->equals($signActor->getId())) {
            $event->setResponse(new Response(status: Response::HTTP_FORBIDDEN, headers: [
                'Content-Type' => 'application/activity+json'
            ]));
            return;
        }

        $resolvedObject = $this->objectResolver->resolve($object);
        if (null === $resolvedObject) {
            $event->setResponse(new Response(status: Response::HTTP_NOT_FOUND, headers: [
                'Content-Type' => 'application/activity+json'
            ]));
            return;
        }
        if (!$resolvedObject instanceof CoreObject) {
            $event->setResponse(new Response(status: Response::HTTP_BAD_REQUEST, headers: [
                'Content-Type' => 'application/activity+json'
            ]));
            return;
        }

        $attributedTo = $resolvedObject->attributedTo;
        if (null === $attributedTo || null === ($attributedToId = $attributedTo->getSingleValueId())) {
            $event->setResponse(new Response(status: Response::HTTP_BAD_REQUEST, headers: [
                'Content-Type' => 'application/activity+json'
            ]));
            return;
        }

        $localActor = $this->localActorService->findLocalActorByUri($attributedToId);
        if (null === $this->localActorService->findLocalActorByUri($attributedToId)) {
            $event->setResponse(new Response(status: Response::HTTP_BAD_REQUEST, headers: [
                'Content-Type' => 'application/activity+json'
            ]));
            return;
        }

        if (null !== $localActor) {
            $this->interactionService->incoming(
                $activity->getId(),
                $activity->getType(),
                $activityActorId,
                $resolvedObject->getId()
            );
        } else {
            // TODO: This is just a notification
        }

        $event->setResponse(new Response(status: Response::HTTP_ACCEPTED, headers: [
            'Content-Type' => 'application/activity+json'
        ]));
    }
}
