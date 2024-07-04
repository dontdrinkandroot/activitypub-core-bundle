<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Override;

class FollowersAction extends AbstractFollowListAction
{
    #[Override]
    protected function getCount(LocalActorInterface $localActor): int
    {
        return $this->followService->getNumFollowers($localActor);
    }

    #[Override]
    protected function listUris(LocalActorInterface $localActor, int $page): array
    {
        return $this->followService->listFollowers(localActor: $localActor, page: $page);
    }

    #[Override]
    protected function generatePageUri(LocalActorInterface $localActor, ?int $page = null): Uri
    {
        return $this->localActorUriGenerator->generateFollowers($localActor, $page);
    }
}
