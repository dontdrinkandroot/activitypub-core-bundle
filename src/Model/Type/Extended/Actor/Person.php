<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor;

class Person extends Actor
{
    public function __construct()
    {
        parent::__construct(ActorType::PERSON);
    }
}
