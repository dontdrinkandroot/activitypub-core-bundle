<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Controller;

use Dontdrinkandroot\ActivityPubCoreBundle\Event\InboxEvent;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\ActivityPubRequest;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\ActivityPubResponse;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\AbstractActivity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\ActivityStreamEncoder;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\Handler\InboxHandlerInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureVerifierInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
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
        private readonly SignatureVerifierInterface $signatureVerifier,
        private readonly SerializerInterface $serializer,
        private iterable $inboxHandlers,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $coreType = $this->serializer->deserialize(
            $request->getContent(),
            CoreType::class,
            ActivityStreamEncoder::FORMAT
        );

        if (!$coreType instanceof AbstractActivity) {
            $this->logger->warning('SharedInbox: Not an Activity', ['type' => $coreType::class]);
            return new JsonResponse(
                data: [
                    '@type' => 'Error',
                    'message' => 'Not an Activity: ' . $coreType->getType()
                ],
                status: Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $activityPubRequest = new ActivityPubRequest($request, $coreType);
        $response = $this->handle($activityPubRequest);
        if (null !== $response) {
            return $response;
        }

        $this->logger->warning('SharedInbox: No handler found', ['type' => $coreType->getType()]);

        //TODO: Replace when all handlers are implemented
        //return new Response('', Response::HTTP_UNPROCESSABLE_ENTITY);
        return new JsonResponse(
            data: ['error' => 'No handler found for ' . $coreType->getType()],
            status: Response::HTTP_NOT_IMPLEMENTED
        );
    }

    private function handle(ActivityPubRequest $activityPubRequest): ?ActivityPubResponse
    {
        foreach ($this->inboxHandlers as $inboxHandler) {
           $response = $inboxHandler->handle($activityPubRequest);
              if (null !== $response) {
                return $response;
              }
        }

        return null;
    }
}
