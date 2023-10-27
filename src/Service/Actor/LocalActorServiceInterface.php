<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectProviderInterface;

interface LocalActorServiceInterface extends ObjectProviderInterface
{
    public function findLocalActorByUsername(string $username): ?LocalActorInterface;

    public function findLocalActorByUri(Uri $uri): ?LocalActorInterface;

    /**
     * TODO: Maybe deprecate and move to object provider
     * @param LocalActorInterface $localActor
     * @return Actor
     */
    public function toActivityPubActor(LocalActorInterface $localActor): Actor;

    public function getSignKey(LocalActorInterface $localActor): SignKey;

    public function provide(Uri $uri, ?SignKey $signKey): Actor|false|null;
}
