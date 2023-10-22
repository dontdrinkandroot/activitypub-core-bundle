<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Controller;

use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorUriGenerator;
use Dontdrinkandroot\Common\Asserted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WebfingerAction extends AbstractController
{
    public function __construct(
        private readonly LocalActorServiceInterface $actorService,
        private readonly LocalActorUriGenerator $localActorUriGenerator
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $resource = Asserted::string($request->query->get('resource'));

        $explodedResource = str_replace('acct:', '', $resource);
        $explodedResource = explode('@', $explodedResource);
        $username = $explodedResource[0];
        $domain = $explodedResource[1];

        $actor = $this->actorService->findLocalActorByUsername($username) ?? throw new NotFoundHttpException();

        $data = [
            'subject' => $resource,
            'links' => [
                [
                    'rel' => 'self',
                    'type' => 'application/activity+json',
                    'href' => (string)$this->localActorUriGenerator->generateId($actor->getUsername()),
                ],
            ],
        ];

        $json = json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return new Response($json, Response::HTTP_OK, ['Content-Type' => 'application/jrd+json']);
    }
}
