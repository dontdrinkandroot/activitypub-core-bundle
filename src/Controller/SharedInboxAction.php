<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Controller;

use Dontdrinkandroot\ActivityPubCoreBundle\DataCollector\ActivityPubDataCollector;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Request\ActivityPubRequest;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Response\ActivityPubErrorResponse;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Response\ActivityPubResponse;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\AbstractActivity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\ActivityStreamEncoder;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\Handler\InboxHandlerInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureVerifierInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class SharedInboxAction extends AbstractController
{
    /**
     * @param iterable<InboxHandlerInterface> $inboxHandlers
     */
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly iterable $inboxHandlers,
        private readonly ActivityPubDataCollector $dataCollector,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $response = $this->handleRequest($request);
        $this->dataCollector->setInboxResult($response->getStatusCode());

        return $response;
    }

    private function handleRequest(Request $request): ActivityPubResponse
    {
        $content = $request->getContent();
        $this->dataCollector->setInboxContent($content);
        $coreType = $this->serializer->deserialize($content, CoreType::class, ActivityStreamEncoder::FORMAT);

        if (!$coreType instanceof AbstractActivity) {
            $this->logger->warning('SharedInbox: Not an Activity', ['type' => $coreType::class]);
            return new ActivityPubErrorResponse(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'Not an Activity: ' . $coreType->getType()
            );
        }

        $activityPubRequest = new ActivityPubRequest($request, $coreType);
        $response = $this->handle($activityPubRequest);
        if (null !== $response) {
            return $response;
        }

        $this->logger->warning('SharedInbox: No handler found', ['type' => $coreType->getType()]);

        //TODO: Replace when all handlers are implemented
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
