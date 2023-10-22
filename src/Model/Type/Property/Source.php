<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property;

class Source
{
    public function __construct(
        public string $content,
        public string $mediaType,
    ) {
    }
}
