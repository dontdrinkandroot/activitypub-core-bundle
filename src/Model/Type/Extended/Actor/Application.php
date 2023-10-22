<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor;

class Application extends Actor
{
    public function __construct()
    {
        parent::__construct(ActorType::APPLICATION);
    }
}
