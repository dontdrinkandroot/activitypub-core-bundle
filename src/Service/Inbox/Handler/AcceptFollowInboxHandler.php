<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\Handler;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowResponseType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Request\ActivityPubRequest;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Response\ActivityPubResponse;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Accept;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Follow;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\InboxServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureVerifierInterface;
use Override;
use Symfony\Component\HttpFoundation\Response;

class AcceptFollowInboxHandler implements InboxHandlerInterface
{
    public function __construct(
        private readonly FollowServiceInterface $followService,
        private readonly ObjectResolverInterface $objectResolver,
        private readonly LocalActorServiceInterface $localActorService,
        private readonly SignatureVerifierInterface $signatureVerifier,
        private readonly InboxServiceInterface $inboxService
    ) {
    }

    #[Override]
    public function handle(ActivityPubRequest $request): ?ActivityPubResponse
    {
        $activity = $request->activity;

        if (
            !($activity instanceof Accept)
            || (null === $activity->object)
            || (null === ($acceptActorId = $activity->actor?->getSingleValueId()))
        ) {
            return null;
        }

        $signActor = $this->signatureVerifier->verify($request);

        if (!$acceptActorId->equals($signActor->getId())) {
            return new ActivityPubResponse(Response::HTTP_FORBIDDEN);
        }

        $follow = $this->objectResolver->resolve($activity->object);
        if (
            !($follow instanceof Follow)
            || (null === ($followActorId = $follow->actor?->getSingleValueId()))
            || (null === ($followObjectId = $follow->object?->getId()))
        ) {
            return null;
        }

        if (!$followObjectId->equals($signActor->getId())) {
            return new ActivityPubResponse(Response::HTTP_FORBIDDEN);
        }

        if (null === ($followActor = $this->localActorService->findLocalActorByUri($followActorId))) {
            return new ActivityPubResponse(Response::HTTP_NOT_FOUND);
        }

        $this->followService->onFollowingResponse($followActor, $signActor->getId(), FollowResponseType::ACCEPTED);
        $this->inboxService->addItem($followActor, $activity);

        return new ActivityPubResponse(Response::HTTP_OK);
    }
}
