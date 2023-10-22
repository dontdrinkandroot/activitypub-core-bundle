<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor;

enum ActorType: string
{
    case APPLICATION = 'Application';
    case GROUP = 'Group';
    case ORGANIZATION = 'Organization';
    case PERSON = 'Person';
    case SERVICE = 'Service';
}
