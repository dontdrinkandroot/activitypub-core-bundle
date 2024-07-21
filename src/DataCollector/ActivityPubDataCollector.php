<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\DataCollector;

use Override;
use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Throwable;

class ActivityPubDataCollector extends AbstractDataCollector
{
    #[Override]
    public function collect(Request $request, Response $response, ?Throwable $exception = null): void
    {
        /* Noop */
        $this->data['example'] = 'working';
    }

    #[Override]
    public static function getTemplate(): ?string
    {
        return '@DdrActivityPubCore/data_collector.html.twig';
    }

    // TODO: Remove
    public function getData(): array
    {
        return $this->data;
    }
}
