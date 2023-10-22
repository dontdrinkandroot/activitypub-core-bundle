<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor;

class Service extends Actor
{
    public function __construct()
    {
        parent::__construct(ActorType::SERVICE);
    }
}
