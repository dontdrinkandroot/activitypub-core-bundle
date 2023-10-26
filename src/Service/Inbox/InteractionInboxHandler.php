<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\AbstractActivity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Announce;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Dislike;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Like;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\LocalObjectResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Share\InteractionServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class InteractionInboxHandler implements InboxHandlerInterface
{
    public function __construct(
        private readonly LocalObjectResolverInterface $localObjectResolver,
        private readonly InteractionServiceInterface $interactionService
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
        // TODO: Avoid duplications as the announce is also shared with followers

        if (
            !(($activity instanceof Announce) || ($activity instanceof Like) || ($activity instanceof Dislike))
            || null === ($objectId = $activity->object?->getId())
            || null === ($activityActorId = $activity->actor?->getSingleValueId())
        ) {
            return null;
        }

        if (!$activityActorId->equals($signActorId)) {
            return new Response(status: Response::HTTP_FORBIDDEN);
        }

        if (!$this->localObjectResolver->hasObject($objectId)) {
            return new Response(status: Response::HTTP_NOT_FOUND);
        }

        $this->interactionService->incoming($activity->getId(), $activity->getType(), $activityActorId, $objectId);

        return new Response(status: Response::HTTP_ACCEPTED);
    }
}
