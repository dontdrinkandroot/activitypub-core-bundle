<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Image;
use Override;

/**
 * @extends AbstractLinkable<Image>
 */
class LinkableImage extends AbstractLinkable
{
    #[Override]
    public static function getObjectClass(): string
    {
        return Image::class;
    }
}
