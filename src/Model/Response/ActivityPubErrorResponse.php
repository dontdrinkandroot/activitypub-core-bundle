<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Response;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Response\ActivityPubResponse;

class ActivityPubErrorResponse extends ActivityPubResponse
{
    public function __construct(int $status, string $errorMessage, array $headers = [])
    {
        parent::__construct(
            $status,
            json_encode(
                [
                    'error' => [
                        'code' => $status,
                        'message' => $errorMessage
                    ]
                ],
                JSON_THROW_ON_ERROR
            ),
            $headers
        );
    }
}
