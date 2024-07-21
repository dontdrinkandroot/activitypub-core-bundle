<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Event;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\AbstractActivity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureVerifierInterface;
use Dontdrinkandroot\Common\Asserted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class InboxEvent extends Event
{
    private ?Response $response = null;

    private ?Actor $signActor = null;

    private bool $verified = false;

    public function __construct(
        public readonly Request $request,
        public readonly AbstractActivity $activity,
        private readonly SignatureVerifierInterface $signatureVerifier,
        public readonly ?LocalActorInterface $inboxOwner = null
    ) {
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function setResponse(?Response $response): void
    {
        $this->response = $response;
    }

    public function verify(): Actor
    {
        if (!$this->verified) {
            $this->signActor = $this->signatureVerifier->verify($this->request);
            $this->verified = true;
        }

        return Asserted::notNull($this->signActor);
    }
}
