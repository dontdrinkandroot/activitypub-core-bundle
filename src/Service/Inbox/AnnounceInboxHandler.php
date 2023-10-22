<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\AbstractActivity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Announce;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\LocalObjectResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Share\ShareServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class AnnounceInboxHandler implements InboxHandlerInterface
{
    public function __construct(
        private readonly LocalObjectResolverInterface $localObjectResolver,
        private readonly ShareServiceInterface $shareService
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function handle(AbstractActivity $activity, Uri $signActorId, ?LocalActorInterface $inboxActor): ?Response
    {
        if (
            !($activity instanceof Announce)
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

        $this->shareService->shared($activityActorId, $objectId);

        return new Response(status: Response::HTTP_ACCEPTED);
    }
}
