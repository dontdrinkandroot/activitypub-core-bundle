<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Symfony\Component\HttpFoundation\Request;

interface SignatureVerifierInterface
{
    /**
     * @return Uri The Uri of the Actor that signed the request.
     */
    public function verifyRequest(Request $request): Uri;
}
