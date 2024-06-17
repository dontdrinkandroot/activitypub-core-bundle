<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Override;

class Place extends CoreObject
{
    public ?float $accuracy = null; // TODO: xsd:float [>= 0.0f, <= 100.0f]

    public ?float $altitude = null;

    public ?float $latitude = null;

    public ?float $longitude = null;

    public ?float $radius = null; // TODO: xsd:float [>= 0.0f]

    public ?string $units = null; // TODO: "cm" | " feet" | " inches" | " km" | " m" | " miles" | xsd:anyURI

    #[Override]
    public function getType(): string
    {
        return ObjectType::PLACE->value;
    }
}
