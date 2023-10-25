<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetAction extends AbstractController
{
    public function __construct(
        private readonly LocalActorServiceInterface $actorService
    ) {
    }

    public function __invoke(Request $request, string $username): Actor
    {
        $localActor = $this->actorService->findLocalActorByUsername($username) ?? throw new NotFoundHttpException();
        return $this->actorService->toActivityPubActor($localActor);
    }
}
