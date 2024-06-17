<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\IntransitiveActivity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObjectsCollection;
use Override;

class Question extends IntransitiveActivity
{
    public ?LinkableObjectsCollection $oneOf = null;

    public ?LinkableObjectsCollection $anyOf = null;

    public ?LinkableObject $closed = null; // TODO: Object | Link | xsd:dateTime | xsd:boolean

    #[Override]
    public function getType(): string
    {
        return IntransitiveActivityType::QUESTION->value;
    }
}
