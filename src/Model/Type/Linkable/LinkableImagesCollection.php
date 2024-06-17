<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Image;
use Override;

/**
 * @extends AbstractLinkableCollection<Image,LinkableImage>
 */
class LinkableImagesCollection extends AbstractLinkableCollection
{
    #[Override]
    public static function getLinkableClass(): string
    {
        return LinkableImage::class;
    }
}
