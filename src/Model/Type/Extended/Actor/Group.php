<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor;

class Group extends Actor
{
    public function __construct()
    {
        parent::__construct(ActorType::GROUP);
    }
}
