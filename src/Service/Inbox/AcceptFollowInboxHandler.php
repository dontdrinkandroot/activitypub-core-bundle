<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowResponseType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\AbstractActivity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Accept;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Follow;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Symfony\Component\HttpFoundation\Response;

class AcceptFollowInboxHandler implements InboxHandlerInterface
{
    public function __construct(
        private readonly FollowServiceInterface $followService,
        private readonly ObjectResolverInterface $objectResolver,
        private readonly LocalActorServiceInterface $localActorService
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function handle(AbstractActivity $activity, Uri $signActorId, ?LocalActorInterface $inboxActor = null): ?Response
    {
        if (
            !($activity instanceof Accept)
            || (null === $activity->object)
            || (null === ($acceptActorId = $activity->actor?->getSingleValueId()))
        ) {
            return null;
        }

        if (!$acceptActorId->equals($signActorId)) {
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

        if (!$followObjectId->equals($signActorId)) {
            return new Response(status: Response::HTTP_FORBIDDEN);
        }

        if (null === ($localActor = $this->localActorService->findLocalActorByUri($followActorId))) {
            return new Response(status: Response::HTTP_NOT_FOUND);
        }

        $this->followService->onFollowingResponse($localActor, $signActorId, FollowResponseType::ACCEPTED);

        return new Response(status: Response::HTTP_ACCEPTED);
    }
}
