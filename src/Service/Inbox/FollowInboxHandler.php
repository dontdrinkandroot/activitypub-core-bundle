<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\AbstractActivity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Follow;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowerStorageInterface;
use Symfony\Component\HttpFoundation\Response;

class FollowInboxHandler implements InboxHandlerInterface
{
    public function __construct(
        private readonly LocalActorServiceInterface $localActorService,
        private readonly FollowerStorageInterface $followerStorage
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function handle(
        AbstractActivity $activity,
        Uri $signActorId,
        ?LocalActorInterface $inboxActor = null
    ): ?Response
    {
        if (
            (!$activity instanceof Follow)
            || (null === ($targetObject = $activity->object))
            || (null === ($remoteActorId = $activity->actor?->getSingleValueId()))
        ) {
            return null;
        }

        if (!$signActorId->equals($remoteActorId)) {
            return new Response(status: Response::HTTP_FORBIDDEN);
        }

        if (null === ($targetLocalActor = $this->localActorService->findLocalActorByUri($targetObject->getId()))) {
            return new Response(status: Response::HTTP_NOT_FOUND);
        }

        $this->followerStorage->add($targetLocalActor, $remoteActorId);

        return new Response(status: Response::HTTP_ACCEPTED);
    }
}
