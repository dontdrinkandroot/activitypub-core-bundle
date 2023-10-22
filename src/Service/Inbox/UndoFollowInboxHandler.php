<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\AbstractActivity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Follow;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Undo;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowerStorageInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Symfony\Component\HttpFoundation\Response;

class UndoFollowInboxHandler implements InboxHandlerInterface
{
    public function __construct(
        private readonly FollowerStorageInterface $followerService,
        private readonly ObjectResolverInterface $objectResolver,
        private readonly LocalActorServiceInterface $localActorService,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function handle(AbstractActivity $activity, Uri $signActorId, ?LocalActorInterface $inboxActor): ?Response
    {
        if (
            !($activity instanceof Undo)
            || (null === $activity->object)
            || (null === ($undoActorId = $activity->actor?->getSingleValueId()))
        ) {
            return null;
        }

        if (!$undoActorId->equals($signActorId)) {
            return new Response(status: Response::HTTP_FORBIDDEN);
        }

        $follow = $this->objectResolver->resolve($activity->object);
        if (
            !($follow instanceof Follow)
            || (null === ($followActorId = $follow->actor?->getSingleValueId()))
            || (null === ($followObjectId = $follow->object?->getId()))
        ) {
            return null;
        }

        if (!$followActorId->equals($signActorId)) {
            return new Response(status: Response::HTTP_FORBIDDEN);
        }

        if (null === ($localActor = $this->localActorService->findLocalActorByUri($followObjectId))) {
            return new Response(status: Response::HTTP_NOT_FOUND);
        }

        $this->followerService->remove($localActor, $followActorId);

        return new Response(status: Response::HTTP_ACCEPTED);
    }
}
