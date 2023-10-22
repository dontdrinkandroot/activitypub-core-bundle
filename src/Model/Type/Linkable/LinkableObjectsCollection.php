<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Link;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\Common\Asserted;

/**
 * @extends AbstractLinkableCollection<CoreObject, LinkableObject>
 */
class LinkableObjectsCollection extends AbstractLinkableCollection
{
    /**
     * {@inheritdoc}
     */
    public static function getLinkableClass(): string
    {
        return LinkableObject::class;
    }

    public static function singleLinkFromObject(CoreObject $object): LinkableObjectsCollection
    {
        $collection = new static();
        $collection->append(new LinkableObject(link: Link::fromUri(Asserted::notNull($object->id))));

        return $collection;
    }

    public static function singleLinkFromUri(Uri $uri): LinkableObjectsCollection
    {
        $collection = new static();
        $collection->append(new LinkableObject(link: Link::fromUri($uri)));

        return $collection;
    }

    public function getSingleValueId(): ?Uri
    {
        if (!$this->isSingleValued()) {
            return null;
        }

        return $this->offsetGet(0)->getId();
    }
}
