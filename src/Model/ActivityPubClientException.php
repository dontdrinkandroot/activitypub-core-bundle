<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model;

use Exception;
use Throwable;

class ActivityPubClientException extends Exception
{
    public function __construct(
        string $message,
        int $code,
        string $method,
        string $uri,
        ?Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}
