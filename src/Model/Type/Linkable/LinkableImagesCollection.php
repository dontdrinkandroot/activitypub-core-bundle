<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Image;

/**
 * @extends AbstractLinkableCollection<Image,LinkableImage>
 */
class LinkableImagesCollection extends AbstractLinkableCollection
{
    /**
     * {@inheritdoc}
     */
    public static function getLinkableClass(): string
    {
        return LinkableImage::class;
    }
}
