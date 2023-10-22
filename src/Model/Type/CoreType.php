<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type;

abstract class CoreType
{
    public ?JsonLdContext $jsonLdContext = null;

    public ?array $additionalProperties = null;

    abstract public function getType(): string;

    public function hasAdditionalProperties(): bool
    {
        return null !== $this->additionalProperties && count($this->additionalProperties) > 0;
    }
}
