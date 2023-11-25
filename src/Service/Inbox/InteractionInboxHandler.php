<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\AbstractActivity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Announce;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Dislike;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Like;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Share\InteractionServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class InteractionInboxHandler implements InboxHandlerInterface
{
    public function __construct(
        private readonly ObjectResolverInterface $objectResolver,
        private readonly InteractionServiceInterface $interactionService,
        private readonly LocalActorServiceInterface $localActorService
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function handle(
        AbstractActivity $activity,
        Uri $signActorId,
        ?LocalActorInterface $inboxActor = null
    ): ?Response {
        if (
            !(($activity instanceof Announce) || ($activity instanceof Like) || ($activity instanceof Dislike))
            || null === ($object = $activity->object)
            || null === ($activityActorId = $activity->actor?->getSingleValueId())
        ) {
            return null;
        }

        if (!$activityActorId->equals($signActorId)) {
            return new Response(status: Response::HTTP_FORBIDDEN, headers: [
                'Content-Type' => 'application/activity+json'
            ]);
        }

        $resolvedObject = $this->objectResolver->resolve($object);
        if (null === $resolvedObject) {
            return new Response(status: Response::HTTP_NOT_FOUND, headers: [
                'Content-Type' => 'application/activity+json'
            ]);
        }
        if (!$resolvedObject instanceof CoreObject) {
            return new Response(status: Response::HTTP_BAD_REQUEST, headers: [
                'Content-Type' => 'application/activity+json'
            ]);
        }

        $attributedTo = $resolvedObject->attributedTo;
        if (null === $attributedTo || null === ($attributedToId = $attributedTo->getSingleValueId())) {
            return new Response(status: Response::HTTP_BAD_REQUEST, headers: [
                'Content-Type' => 'application/activity+json'
            ]);
        }

        $localActor = $this->localActorService->findLocalActorByUri($attributedToId);
        if (null === $this->localActorService->findLocalActorByUri($attributedToId)) {
            return new Response(status: Response::HTTP_BAD_REQUEST, headers: [
                'Content-Type' => 'application/activity+json'
            ]);
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

        return new Response(status: Response::HTTP_ACCEPTED, headers: [
            'Content-Type' => 'application/activity+json'
        ]);
    }
}
