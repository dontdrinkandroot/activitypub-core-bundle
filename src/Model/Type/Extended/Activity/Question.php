<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\IntransitiveActivity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObjectsCollection;

class Question extends IntransitiveActivity
{
    public ?LinkableObjectsCollection $oneOf = null;

    public ?LinkableObjectsCollection $anyOf = null;

    public ?LinkableObject $closed = null; // TODO: Object | Link | xsd:dateTime | xsd:boolean

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return IntransitiveActivityType::QUESTION->value;
    }
}
