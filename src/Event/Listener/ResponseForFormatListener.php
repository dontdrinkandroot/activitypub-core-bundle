<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Event\Listener;

use Dontdrinkandroot\ActivityPubCoreBundle\Config\Route\RouteName;
use Dontdrinkandroot\ActivityPubCoreBundle\DataCollector\ActivityPubDataCollector;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\ActivityStreamEncoder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;

class ResponseForFormatListener
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ActivityPubDataCollector $activityPubDataCollector
    ) {
    }

    public function onView(ViewEvent $event): void
    {
        $request = $event->getRequest();
        $route = $request->attributes->get('_route');
        if (!is_string($route) || !str_starts_with($route, RouteName::DDR_ACTIVITYPUB_PREFIX)) {
            return;
        }

        $data = $event->getControllerResult();
        $format = $request->getPreferredFormat('jsonld');
        if ($data instanceof CoreType) {
            if (!in_array($format, ['json', 'jsonld'])) {
                $event->setResponse(new Response('', 406));
                return;
            }

            $json = $this->serializer->serialize($data, ActivityStreamEncoder::FORMAT);
            $response = new Response($json, 200, [
                'Content-Type' => 'application/activity+json',
            ]);
            $this->activityPubDataCollector->setResponseJson($json);
            $event->setResponse($response);
        }
    }
}
