<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

interface LocalActorUriGeneratorInterface
{
    public function generateSharedInbox(): Uri;

    public function generateId(LocalActorInterface|string $usernameOrLocalActor): Uri;

    public function generateInbox(LocalActorInterface|string $usernameOrLocalActor): Uri;

    public function generateOutbox(LocalActorInterface|string $usernameOrLocalActor): Uri;

    public function generateFollowers(LocalActorInterface|string $usernameOrLocalActor, ?int $page = null): Uri;

    public function generateFollowing(LocalActorInterface|string $usernameOrLocalActor, ?int $page = null): Uri;

    public function matchUsername(Uri $uri): ?string;
}
