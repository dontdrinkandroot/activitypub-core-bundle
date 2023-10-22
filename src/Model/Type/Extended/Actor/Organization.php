<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor;

class Organization extends Actor
{
    public function __construct()
    {
        parent::__construct(ActorType::ORGANIZATION);
    }
}
