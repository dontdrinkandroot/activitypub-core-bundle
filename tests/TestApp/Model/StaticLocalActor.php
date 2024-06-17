<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Model;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Override;

class StaticLocalActor implements LocalActorInterface
{
    public function __construct(
        public readonly string $username
    ) {
    }

    #[Override]
    public function getUsername(): string
    {
        return $this->username;
    }
}
