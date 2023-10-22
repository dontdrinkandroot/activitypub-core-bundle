<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Image;

/**
 * @extends AbstractLinkable<Image>
 */
class LinkableImage extends AbstractLinkable
{
    /**
     * {@inheritdoc}
     */
    public static function getObjectClass(): string
    {
        return Image::class;
    }
}
