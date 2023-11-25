<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\AbstractActivity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Follow;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class FollowInboxHandler implements InboxHandlerInterface
{
    public function __construct(
        private readonly LocalActorServiceInterface $localActorService,
        private readonly FollowServiceInterface $followService
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
            return new Response(status: Response::HTTP_FORBIDDEN, headers: [
                'Content-Type' => 'application/activity+json'
            ]);
        }

        if (null === ($targetLocalActor = $this->localActorService->findLocalActorByUri($targetObject->getId()))) {
            return new Response(status: Response::HTTP_NOT_FOUND, headers: [
                'Content-Type' => 'application/activity+json'
            ]);
        }

        $this->followService->onFollowerRequest($targetLocalActor, $remoteActorId);

        return new Response(status: Response::HTTP_ACCEPTED, headers: [
            'Content-Type' => 'application/activity+json'
        ]);
    }
}
