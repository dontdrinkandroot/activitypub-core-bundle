<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Override;

class FollowingAction extends AbstractFollowListAction
{
    #[Override]
    protected function getCount(LocalActorInterface $localActor): int
    {
        return $this->followService->getNumFollowing($localActor);
    }

    #[Override]
    protected function listUris(LocalActorInterface $localActor, int $page): array
    {
        return $this->followService->listFollowing(localActor: $localActor, page: $page);
    }

    #[Override]
    protected function generatePageUri(LocalActorInterface $localActor, ?int $page = null): Uri
    {
        return $this->localActorUriGenerator->generateFollowing($localActor, $page);
    }
}
