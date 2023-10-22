<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CollectionPage;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Link;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

/**
 * @extends AbstractLinkable<CollectionPage>
 */
class LinkableCollectionPage extends AbstractLinkable
{
    /**
     * {@inheritdoc}
     */
    public static function getObjectClass(): string
    {
        return CollectionPage::class;
    }

    public static function linkFromObject(CollectionPage $object): static
    {
        return new static(link: Link::fromUri($object->getId()));
    }

    public static function linkFromUri(Uri $uri): static
    {
        return new static(link: Link::fromUri($uri));
    }

    public static function fromObject(CollectionPage $object): static
    {
        return new static(object: $object);
    }
}
