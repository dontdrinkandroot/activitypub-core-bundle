<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Collection;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Link;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

/**
 * @extends AbstractLinkable<Collection>
 */
class LinkableCollection extends AbstractLinkable
{
    /**
     * {@inheritdoc}
     */
    public static function getObjectClass(): string
    {
        return Collection::class;
    }

    public static function linkFromObject(Collection $object): static
    {
        return new static(link: Link::fromUri($object->getId()));
    }

    public static function linkFromUri(Uri $uri): static
    {
        return new static(link: Link::fromUri($uri));
    }

    public static function fromObject(Collection $object): static
    {
        return new static(object: $object);
    }
}
