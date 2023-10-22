<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;

interface SignatureGeneratorInterface
{
    public function generateSignatureHeader(string $method, string $path, SignKey $key, array $headers): string;
}
