<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\OrderedCollection;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\OrderedCollectionPage;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableCollection;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableCollectionPage;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObjectsCollection;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorUriGeneratorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FollowersAction
{
    public function __construct(
        private readonly FollowServiceInterface $followService,
        private readonly LocalActorServiceInterface $localActorService,
        private readonly LocalActorUriGeneratorInterface $localActorUriGenerator
    ) {
    }

    public function __invoke(Request $request, string $username): CoreType
    {
        $localActor = $this->localActorService->findLocalActorByUsername($username)
            ?? throw new NotFoundHttpException();
        $page = $request->query->getInt('page');
        if ($page < 0) {
            throw new NotFoundHttpException();
        }

        $numFollowers = $this->followService->getNumFollowers($localActor);

        if (0 === $page) {
            return $this->getOverview($localActor, $numFollowers);
        }

        return $this->getPage($localActor, $page, $numFollowers);
    }

    private function getOverview(
        LocalActorInterface $localActor,
        int $numFollowers
    ): OrderedCollection {
        $orderedCollection = new OrderedCollection();
        $orderedCollection->id = $this->localActorUriGenerator->generateFollowers($localActor);
        $orderedCollection->totalItems = $numFollowers;
        if ($numFollowers > 0) {
            $orderedCollection->first = LinkableCollectionPage::linkFromUri(
                $this->localActorUriGenerator->generateFollowers($localActor, 1)
            );
        }
        return $orderedCollection;
    }

    private function getPage(
        LocalActorInterface $localActor,
        int $page,
        int $numFollowers
    ): OrderedCollectionPage {
        $followers = $this->followService->listFollowers(localActor: $localActor, page: $page);
        $orderedCollectionPage = new OrderedCollectionPage();
        $orderedCollectionPage->id = $this->localActorUriGenerator->generateFollowers($localActor, $page);
        $orderedCollectionPage->totalItems = $numFollowers;
        $orderedCollectionPage->partOf = LinkableCollection::linkFromUri(
            $this->localActorUriGenerator->generateFollowers($localActor)
        );
        if ($page > 1) {
            $orderedCollectionPage->prev = LinkableCollectionPage::linkFromUri(
                $this->localActorUriGenerator->generateFollowers($localActor, $page - 1)
            );
        }
        if ($numFollowers > $page * 50) {
            $orderedCollectionPage->next = LinkableCollectionPage::linkFromUri(
                $this->localActorUriGenerator->generateFollowers($localActor, $page + 1)
            );
        }
        $orderedItems = new LinkableObjectsCollection();
        foreach ($followers as $follower) {
            $orderedItems->append(LinkableObject::linkFromUri($follower));
        }
        $orderedCollectionPage->orderedItems = $orderedItems;

        return $orderedCollectionPage;
    }
}
