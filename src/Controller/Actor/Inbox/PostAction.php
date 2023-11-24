<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\AbstractActivity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\ActivityStreamEncoder;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\InboxHandlerInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureVerifierInterface;
use Psr\Log\LoggerInterface;
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
        private readonly iterable $handlers,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(Request $request, string $username): Response
    {
        if (null === ($localActor = $this->localActorService->findLocalActorByUsername($username))) {

            $this->logger->warning('Inbox: Actor not found', ['username' => $username]);

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

            $this->logger->warning('Inbox: Not an Activity', ['username' => $username, 'type' => get_class($coreType)]);

            return new JsonResponse(
                data: ['error' => 'Not an Activity'],
                status: Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        foreach ($this->handlers as $handler) {
            $response = $handler->handle($coreType, $signActorId, $localActor);
            if (null !== $response) {

                $this->logger->info('Inbox: Handler found', ['username' => $username, 'type' => $coreType->getType(), 'handler' => get_class($handler)]);

                return $response;
            }
        }

        $this->logger->warning('Inbox: No handler found', ['username' => $username, 'type' => $coreType->getType()]);

        //TODO: Replace when all handlers are implemented
        //return new Response('', Response::HTTP_UNPROCESSABLE_ENTITY);
        return new JsonResponse(
            data: ['error' => 'No handler found for ' . $coreType->getType()],
            status: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
