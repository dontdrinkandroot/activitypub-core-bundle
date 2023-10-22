<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor as ActivityPubActor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\Common\Asserted;

class FetchingActorResolver implements ActorResolverInterface
{
    public function __construct(
        private readonly ActivityPubClientInterface $activityPubClient,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Uri $actorId): ?Actor
    {
        $coreType = $this->activityPubClient->request('GET', $actorId);
        return Asserted::instanceOf($coreType, ActivityPubActor::class);
    }

    /**
     * {@inheritdoc}
     */
    public function resolvePublicKey(Uri $actorId): ?string
    {
        return $this->resolve($actorId)?->publicKey?->publicKeyPem;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveInbox(Uri $actorId): ?Uri
    {
        return $this->resolve($actorId)?->inbox;
    }
}
