<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property;

use ArrayObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Link;

/**
 * @extends ArrayObject<array-key,Link>
 */
class LinkCollection extends ArrayObject
{
    public function isSingleValued(): bool
    {
        return 1 === $this->count();
    }

    public static function create(Link|Uri|string...$links): self
    {
        $convertedLinks = [];
        foreach ($links as $link) {
            $convertedLinks[] = match (true) {
                $link instanceof Link => $link,
                $link instanceof Uri => Link::fromUri($link),
                is_string($link) => Link::fromUriString($link),
            };
        }

        return new self($convertedLinks);
    }
}
