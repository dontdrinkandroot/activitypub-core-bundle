<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Link;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

/**
 * @extends AbstractLinkable<CoreObject>
 */
class LinkableObject extends AbstractLinkable
{
    /**
     * {@inheritdoc}
     */
    public static function getObjectClass(): string
    {
        return CoreObject::class;
    }

    public static function linkFromObject(CoreObject $object): static
    {
        return new static(link: Link::fromUri($object->getId()));
    }

    public static function linkFromUri(Uri $uri): static
    {
        return new static(link: Link::fromUri($uri));
    }

    public static function fromObject(CoreObject $object): static
    {
        return new static(object: $object);
    }
}
