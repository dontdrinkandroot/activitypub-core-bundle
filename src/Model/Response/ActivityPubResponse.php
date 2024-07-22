<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Response;

use Symfony\Component\HttpFoundation\Response;

class ActivityPubResponse extends Response
{
    public const string CONTENT_TYPE = 'application/activity+json';

    public function __construct(int $status, string $content = '', array $headers = [])
    {
        parent::__construct($content, $status, $headers + ['Content-Type' => self::CONTENT_TYPE]);
    }
}
