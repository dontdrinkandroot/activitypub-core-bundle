<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\DataCollector\ActivityPubDataCollector;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Request\ActivityPubRequest;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Response\ActivityPubErrorResponse;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Response\ActivityPubResponse;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\AbstractActivity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\ActivityStreamEncoder;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\Handler\InboxHandlerInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureVerifierInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class PostAction extends AbstractController
{
    /**
     * @param iterable<InboxHandlerInterface> $inboxHandlers
     */
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly LocalActorServiceInterface $localActorService,
        private readonly iterable $inboxHandlers,
        private readonly ActivityPubDataCollector $dataCollector,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(Request $request, string $username): Response
    {
        $response = $this->handleRequest($username, $request);
        $this->dataCollector->setInboxResult($response->getStatusCode());

        return $response;
    }

    private function handleRequest(string $username, Request $request): ActivityPubResponse
    {
        $content = $request->getContent();
        $this->dataCollector->setInboxContent($content);
        $this->dataCollector->setUsername($username);

        if (null === ($localActor = $this->localActorService->findLocalActorByUsername($username))) {
            $this->logger->warning('Inbox: Actor not found', ['username' => $username]);
            return new ActivityPubErrorResponse(Response::HTTP_NOT_FOUND, 'Actor not found');
        }

        $coreType = $this->serializer->deserialize($content, CoreType::class, ActivityStreamEncoder::FORMAT);

        if (!$coreType instanceof AbstractActivity) {
            $this->logger->warning('Inbox: Not an Activity', ['username' => $username, 'type' => $coreType::class]);
            return new ActivityPubErrorResponse(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'Not an Activity: ' . $coreType->getType()
            );
        }

        $activityPubRequest = new ActivityPubRequest($request, $coreType, $localActor);
        $response = $this->handle($activityPubRequest);
        if (null !== $response) {
            return $response;
        }

        $this->logger->warning('Inbox: No handler found', ['username' => $username, 'type' => $coreType->getType()]);

        //TODO: Replace when all handlers are implemented
        //return new Response('', Response::HTTP_UNPROCESSABLE_ENTITY);
        return new ActivityPubErrorResponse(
            Response::HTTP_NOT_IMPLEMENTED,
            'No handler found for ' . $coreType->getType()
        );
    }

    private function handle(ActivityPubRequest $activityPubRequest): ?ActivityPubResponse
    {
        foreach ($this->inboxHandlers as $inboxHandler) {
            $response = $inboxHandler->handle($activityPubRequest);
            if (null !== $response) {
                $this->dataCollector->setInboxHandler($inboxHandler::class);
                return $response;
            }
        }

        return null;
    }
}
