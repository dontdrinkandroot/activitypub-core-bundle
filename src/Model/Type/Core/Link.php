<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Override;

class Link extends CoreType
{
    public const string TYPE = 'Link';

    public ?Uri $href = null;

    public ?string $rel = null; // TODO: To object?

    public ?string $mediaType = null; // TODO: To object?

    public ?string $name = null; // TODO: Add langString

    public ?string $hreflang = null;  // TODO: To object?

    public ?int $height = null; // TODO: Validate positive integer

    public ?int $width = null; // TODO: Validate positive integer

    public ?LinkableObject $preview = null;

    public static function fromUri(Uri $id): Link
    {
        $link = new static();
        $link->href = $id;

        return $link;
    }

    #[Override]
    public function getType(): string
    {
        return self::TYPE;
    }

    public function hasOnlyHref(): bool
    {
        return null !== $this->href
            && null === $this->rel
            && null === $this->mediaType
            && null === $this->name
            && null === $this->hreflang
            && null === $this->height
            && null === $this->width
            && null === $this->preview
            && !$this->hasAdditionalProperties()
            && null === $this->jsonLdContext;
    }
}
