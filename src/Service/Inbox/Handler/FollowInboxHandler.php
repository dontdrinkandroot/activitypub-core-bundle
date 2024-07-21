<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\Handler;

use Dontdrinkandroot\ActivityPubCoreBundle\Event\InboxEvent;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\ActivityPubRequest;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\ActivityPubResponse;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Follow;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureVerifierInterface;
use Override;
use Symfony\Component\HttpFoundation\Response;

class FollowInboxHandler implements InboxHandlerInterface
{
    public function __construct(
        private readonly LocalActorServiceInterface $localActorService,
        private readonly FollowServiceInterface $followService,
        private readonly SignatureVerifierInterface $signatureVerifier
    ) {
    }

    #[Override]
    public function handle(ActivityPubRequest $request): ?ActivityPubResponse
    {
        $activity = $request->activity;
        if (
            (!$activity instanceof Follow)
            || (null === ($targetObject = $activity->object))
            || (null === ($remoteActorId = $activity->actor?->getSingleValueId()))
        ) {
            return null;
        }

        $signActor = $this->signatureVerifier->verify($request);
        if (!$remoteActorId->equals($signActor->getId())) {
            return new ActivityPubResponse(Response::HTTP_FORBIDDEN);
        }

        if (null === ($targetLocalActor = $this->localActorService->findLocalActorByUri($targetObject->getId()))) {
            return new ActivityPubResponse(Response::HTTP_NOT_FOUND);
        }

        $this->followService->onFollowerRequest($targetLocalActor, $remoteActorId);

        return new ActivityPubResponse(Response::HTTP_ACCEPTED);
    }
}
