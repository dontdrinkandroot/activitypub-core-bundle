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
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorUriGeneratorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class AbstractFollowListAction extends AbstractController
{
    public function __construct(
        protected readonly FollowServiceInterface $followService,
        protected readonly LocalActorServiceInterface $localActorService,
        protected readonly LocalActorUriGeneratorInterface $localActorUriGenerator
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

        $count = $this->getCount($localActor);

        return (0 === $page)
            ? $this->getOverview($localActor, $count)
            : $this->getPage($localActor, $page, $count);
    }

    private function getOverview(
        LocalActorInterface $localActor,
        int $numFollowers
    ): OrderedCollection {
        $orderedCollection = new OrderedCollection();
        $orderedCollection->id = $this->generatePageUri($localActor);
        $orderedCollection->totalItems = $numFollowers;
        if ($numFollowers > 0) {
            $orderedCollection->first = LinkableCollectionPage::linkFromUri(
                $this->generatePageUri($localActor, 1)
            );
        }
        return $orderedCollection;
    }

    private function getPage(
        LocalActorInterface $localActor,
        int $page,
        int $count
    ): OrderedCollectionPage {
        $uris = $this->listUris($localActor, $page);
        $orderedCollectionPage = new OrderedCollectionPage();
        $orderedCollectionPage->id = $this->generatePageUri($localActor, $page);
        $orderedCollectionPage->totalItems = $count;
        $orderedCollectionPage->partOf = LinkableCollection::linkFromUri($this->generatePageUri($localActor));
        if ($page > 1) {
            $orderedCollectionPage->prev = LinkableCollectionPage::linkFromUri(
                $this->generatePageUri($localActor, $page - 1)
            );
        }
        if ($count > $page * 50) {
            $orderedCollectionPage->next = LinkableCollectionPage::linkFromUri(
                $this->generatePageUri($localActor, $page + 1)
            );
        }
        $orderedItems = new LinkableObjectsCollection();
        foreach ($uris as $uri) {
            $orderedItems->append(LinkableObject::linkFromUri($uri));
        }
        $orderedCollectionPage->orderedItems = $orderedItems;

        return $orderedCollectionPage;
    }

    abstract protected function getCount(LocalActorInterface $localActor): int;

    /** @return Uri[] */
    abstract protected function listUris(LocalActorInterface $localActor, int $page): array;

    abstract protected function generatePageUri(LocalActorInterface $localActor, ?int $page = null): Uri;
}
