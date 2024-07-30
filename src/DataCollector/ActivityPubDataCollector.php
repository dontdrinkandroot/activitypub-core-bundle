<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\DataCollector;

use Override;
use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\Cloner\Data;
use Throwable;

class ActivityPubDataCollector extends AbstractDataCollector
{
    #[Override]
    public function collect(Request $request, Response $response, ?Throwable $exception = null): void
    {
        /* Noop */
    }

    #[Override]
    public static function getTemplate(): ?string
    {
        return '@DdrActivityPubCore/data_collector.html.twig';
    }

    // TODO: Remove
    public function getData(): Data|array
    {
        return $this->data;
    }

    public function setInboxHandler(string $handler): void
    {
        $this->findOrCreateInbox();
        $this->data['inbox']['handler'] = $handler;
    }

    public function setInboxResult(int $getStatusCode): void
    {
        $this->findOrCreateInbox();
        $this->data['inbox']['result'] = $getStatusCode;
    }

    public function setInboxContent(string $content): void
    {
        $this->findOrCreateInbox();
        $this->data['inbox']['content'] = json_decode($content);
    }

    public function getInbox(): ?array
    {
        return $this->data['inbox'] ?? null;
    }

    public function setUsername(string $username): void
    {
        $this->findOrCreateInbox();
        $this->data['inbox']['username'] = $username;
    }

    private function findOrCreateInbox(): void {
        if (!isset($this->data['inbox'])) {
            $this->data['inbox'] = [
                'username' => null,
                'content' => null,
                'handler' => null,
                'result' => null
            ];
        }
    }

    public function setResponseJson(string $json): void
    {
        $this->data['response_json'] = $json;
    }

    public function getResponseJson(): ?string
    {
        return $this->data['response_json'] ?? null;
    }
}
