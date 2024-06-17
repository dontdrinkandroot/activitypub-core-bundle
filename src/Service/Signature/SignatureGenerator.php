<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Header;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\Common\Asserted;
use Override;
use phpseclib3\Crypt\RSA;

class SignatureGenerator implements SignatureGeneratorInterface
{
    #[Override]
    public function generateSignatureHeader(string $method, string $path, SignKey $key, array $headers): string
    {
        $headers[Header::REQUEST_TARGET] = SignatureTools::createRequestTargetHeaderValue($method, $path);

        /* Minimum required headers */
        $signHeaderNames = [
            Header::REQUEST_TARGET,
            strtolower(Header::HOST),
            strtolower(Header::DATE),
        ];

        /* Add additional names */
        foreach ($headers as $headerName => $headerValue) {
            if (
                in_array(strtolower($headerName), [strtolower(Header::USER_AGENT), strtolower(Header::ACCEPT_ENCODING)])
                || in_array(strtolower($headerName), $signHeaderNames)
            ) {
                continue;
            }

            $signHeaderNames[] = strtolower($headerName);
        }

        $signatureString = SignatureTools::buildSignatureString($signHeaderNames, $headers);
        $privateKey = Asserted::instanceOf(RSA::loadPrivateKey($key->privateKeyPem), RSA\PrivateKey::class);
        $privateKey = $privateKey->withPadding(RSA::SIGNATURE_PKCS1);
        $signature = $privateKey->sign($signatureString);

        return sprintf(
            'keyId="%s",algorithm="rsa-sha256",headers="%s",signature="%s"',
            (string)$key->id,
            implode(' ', $signHeaderNames),
            base64_encode((string)$signature)
        );
    }
}
