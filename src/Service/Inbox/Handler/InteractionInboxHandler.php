<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\Handler;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Request\ActivityPubRequest;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Response\ActivityPubResponse;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Announce;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Dislike;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Like;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\InboxServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Share\InteractionServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureVerifierInterface;
use Override;
use Symfony\Component\HttpFoundation\Response;

class InteractionInboxHandler implements InboxHandlerInterface
{
    public function __construct(
        private readonly ObjectResolverInterface $objectResolver,
        private readonly InteractionServiceInterface $interactionService,
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
            !(($activity instanceof Announce) || ($activity instanceof Like) || ($activity instanceof Dislike))
            || null === ($object = $activity->object)
            || null === ($activityActorId = $activity->actor?->getSingleValueId())
        ) {
            return null;
        }

        $signActor = $this->signatureVerifier->verify($request);
        if (!$activityActorId->equals($signActor->getId())) {
            return new ActivityPubResponse(Response::HTTP_FORBIDDEN);
        }

        $resolvedObject = $this->objectResolver->resolve($object);
        if (null === $resolvedObject) {
            return new ActivityPubResponse(Response::HTTP_NOT_FOUND);
        }
        if (!$resolvedObject instanceof CoreObject) {
            return new ActivityPubResponse(Response::HTTP_BAD_REQUEST);
        }

        $attributedTo = $resolvedObject->attributedTo;
        if (null === $attributedTo || null === ($attributedToId = $attributedTo->getSingleValueId())) {
            return new ActivityPubResponse(Response::HTTP_BAD_REQUEST);
        }

        $localActor = $this->localActorService->findLocalActorByUri($attributedToId);
        if (null === $localActor) {
            return new ActivityPubResponse(Response::HTTP_BAD_REQUEST);
        }

//        if (null !== $localActor) {
//            $this->interactionService->incoming(
//                $activity->getId(),
//                $activity->getType(),
//                $activityActorId,
//                $resolvedObject->getId()
//            );
//            $this->inboxService->addItem($localActor, $activity);
//        } else {
//            // TODO: This is just a notification
//        }
        $this->inboxService->addItem($localActor, $activity);

        return new ActivityPubResponse(Response::HTTP_ACCEPTED);
    }
}
