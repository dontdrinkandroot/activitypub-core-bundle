<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Request;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\AbstractActivity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureVerifierInterface;
use Symfony\Component\HttpFoundation\Request;

class ActivityPubRequest
{
    private ?Actor $signActor = null;

    private bool $verified = false;

    public function __construct(
        public readonly Request $request,
        public readonly AbstractActivity $activity,
        public readonly ?LocalActorInterface $inboxOwner = null
    ) {
    }

    public function setVerified(Actor $signActor): void
    {
        $this->signActor = $signActor;
        $this->verified = true;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function getSignActor(): ?Actor
    {
        return $this->signActor;
    }
}
