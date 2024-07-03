<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core;

use DateTimeInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Image;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\ObjectType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableImage;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableImagesCollection;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObjectsCollection;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\LinkCollection;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Source;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Override;
use RuntimeException;

class CoreObject extends CoreType
{
    public ?Uri $id = null;

    public ?LinkableObjectsCollection $attachment = null;

    public ?LinkableObjectsCollection $attributedTo = null;

    public ?LinkableObject $audience = null;

    public ?string $content = null;

    public ?Source $source = null;

    public ?LinkableObject $context = null;

    public ?string $name = null; // TODO: Add langString

    public ?DateTimeInterface $endTime = null;

    public ?LinkableObject $generator = null;

    public ?LinkableImagesCollection $icon = null;

    public ?LinkableImagesCollection $image = null;

    public ?LinkableObject $inReplyTo = null;

    public ?LinkableObjectsCollection $location = null;

    public ?LinkableObject $preview = null;

    public DateTimeInterface|null $published = null;

    public ?Collection $replies = null;

    public ?DateTimeInterface $startTime = null;

    public ?string $summary = null;

    public ?LinkableObjectsCollection $tag = null;

    public ?DateTimeInterface $updated = null;

    public ?LinkCollection $url = null;

    public ?LinkableObjectsCollection $to = null;

    public ?LinkableObjectsCollection $bto = null;

    public ?LinkableObjectsCollection $cc = null;

    public ?LinkableObjectsCollection $bcc = null;

    public ?string $mediaType = null;

    public ?string $duration = null;

    #[Override]
    public function getType(): string
    {
        return ObjectType::OBJECT->value;
    }

    /**
     * Use this function where an ID is expected. It will throw an exception if the ID is not set.
     */
    public function getId(): Uri
    {
        return $this->id ?? throw new RuntimeException('ID not set');
    }

    public function hasId(): bool
    {
        return null !== $this->id;
    }

    public function setIcons(Link|Image|LinkableImage ...$icons): void
    {
        $collection = new LinkableImagesCollection();
        foreach ($icons as $icon) {
            $collection->append(
                match (true) {
                    $icon instanceof Link => new LinkableImage(link: $icon),
                    $icon instanceof Image => new LinkableImage(object: $icon),
                    default => $icon,
                }
            );
        }
    }

    public function setImages(Link|Image|LinkableImage ...$images): void
    {
        $collection = new LinkableImagesCollection();
        foreach ($images as $image) {
            $collection->append(
                match (true) {
                    $image instanceof Link => new LinkableImage(link: $image),
                    $image instanceof Image => new LinkableImage(object: $image),
                    default => $image,
                }
            );
        }
    }
}
