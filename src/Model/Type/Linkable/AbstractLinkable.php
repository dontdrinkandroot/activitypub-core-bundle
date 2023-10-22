<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Link;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\Common\Asserted;
use RuntimeException;

/**
 * @template T of CoreObject
 */
abstract class AbstractLinkable
{
    /** @param T|null $object */
    public function __construct(
        public ?Link $link = null,
        public ?object $object = null
    ) {
    }

    public function isLink(): bool
    {
        return null !== $this->link;
    }

    public function isObject(): bool
    {
        return null !== $this->object;
    }

    public function getId(): Uri
    {
        if (null !== $this->link) {
            return Asserted::notNull($this->link->href);
        }

        if (null !== $this->object) {
            return Asserted::notNull($this->object->id);
        }

        throw new RuntimeException('Linkable has neither link nor object');
    }

    /** @return class-string<T> */
    abstract public static function getObjectClass(): string;
}
