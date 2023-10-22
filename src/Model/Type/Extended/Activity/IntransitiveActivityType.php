<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity;

enum IntransitiveActivityType: string
{
    case INTRANSITIVE_ACTIVITY = 'IntransitiveActivity';
    case ARRIVE = 'Arrive';
    case TRAVEL = 'Travel';
    case QUESTION = 'Question';
}
