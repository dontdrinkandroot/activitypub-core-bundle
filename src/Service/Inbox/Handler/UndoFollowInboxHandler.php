<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\Handler;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Direction;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Request\ActivityPubRequest;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Response\ActivityPubResponse;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Follow;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Undo;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowStorageInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\InboxServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureVerifierInterface;
use Override;
use Symfony\Component\HttpFoundation\Response;

class UndoFollowInboxHandler implements InboxHandlerInterface
{
    public function __construct(
        private readonly FollowStorageInterface $followerStorage,
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
            !($activity instanceof Undo)
            || (null === $activity->object)
            || (null === ($undoActorId = $activity->actor?->getSingleValueId()))
        ) {
            return null;
        }

        $signActor = $this->signatureVerifier->verify($request);
        if (!$undoActorId->equals($signActor->getId())) {
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

        if (!$followActorId->equals($signActor->getId())) {
            return new ActivityPubResponse(Response::HTTP_FORBIDDEN);
        }

        if (null === ($localActor = $this->localActorService->findLocalActorByUri($followObjectId))) {
            return new ActivityPubResponse(Response::HTTP_NOT_FOUND);
        }

        $this->followerStorage->remove($localActor, $followActorId, Direction::INCOMING);
        $this->inboxService->addItem($localActor, $activity);

        return new ActivityPubResponse(Response::HTTP_ACCEPTED);
    }
}
