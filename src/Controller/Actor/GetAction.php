<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorUriGeneratorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetAction extends AbstractController
{
    public function __construct(
        private readonly ObjectResolverInterface $objectResolver,
        private readonly LocalActorUriGeneratorInterface $localActorUriGenerator
    ) {
    }

    public function __invoke(Request $request, string $username): Actor
    {
        $uri = $this->localActorUriGenerator->generateId($username);

        return $this->objectResolver->resolveTyped($uri, Actor::class) ?? throw new NotFoundHttpException();
    }
}
