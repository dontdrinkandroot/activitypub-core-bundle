<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectProviderInterface;

interface LocalActorServiceInterface extends ObjectProviderInterface
{
    public function findLocalActorByUsername(string $username): ?LocalActorInterface;

    public function findLocalActorByUri(Uri $uri): ?LocalActorInterface;

    public function getSignKey(LocalActorInterface $localActor): SignKey;

    public function provide(Uri $uri, ?SignKey $signKey): Actor|false|null;
}
