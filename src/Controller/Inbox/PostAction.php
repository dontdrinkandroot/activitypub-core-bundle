<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Controller\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\AbstractActivity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\ActivityStreamEncoder;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\InboxHandlerInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureVerifierInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class PostAction extends AbstractController
{
    /**
     * @param iterable<InboxHandlerInterface> $handlers
     */
    public function __construct(
        private readonly SignatureVerifierInterface $signatureVerifier,
        private readonly SerializerInterface $serializer,
        private readonly LocalActorServiceInterface $localActorService,
        private readonly iterable $handlers
    ) {
    }

    public function __invoke(Request $request, string $username): Response
    {
        if (null === ($localActor = $this->localActorService->findLocalActorByUsername($username))) {
            return new JsonResponse(
                data: ['error' => 'Actor not found'],
                status: Response::HTTP_NOT_FOUND
            );
        }

        $signActorId = $this->signatureVerifier->verifyRequest($request);
        $coreType = $this->serializer->deserialize(
            $request->getContent(),
            CoreType::class,
            ActivityStreamEncoder::FORMAT
        );
        if (!$coreType instanceof AbstractActivity) {
            return new JsonResponse(
                data: ['error' => 'Not an Activity'],
                status: Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        foreach ($this->handlers as $handler) {
            $response = $handler->handle($coreType, $signActorId, $localActor);
            if (null !== $response) {
                return $response;
            }
        }

        //TODO: Replace when all handlers are implemented
        //return new Response('', Response::HTTP_UNPROCESSABLE_ENTITY);
        return new JsonResponse(
            data: ['error' => 'No handler found for ' . $coreType->getType()],
            status: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
