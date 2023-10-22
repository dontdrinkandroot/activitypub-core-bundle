<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Model;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;

class StaticLocalActor implements LocalActorInterface
{
    public function __construct(
        public readonly string $username
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}
