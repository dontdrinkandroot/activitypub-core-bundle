<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable;

use ArrayObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;

/**
 * @template T of CoreObject
 * @template L of AbstractLinkable<T>
 * @extends ArrayObject<int,L>
 */
abstract class AbstractLinkableCollection extends ArrayObject
{
    public function isSingleValued(): bool
    {
        return 1 === $this->count();
    }

    /**
     * @return L|null
     */
    public function getSingleValue(): ?AbstractLinkable
    {
        if (!$this->isSingleValued()) {
            return null;
        }

        return $this->offsetGet(0);
    }

    /** @return class-string<L> */
    abstract public static function getLinkableClass(): string;
}
