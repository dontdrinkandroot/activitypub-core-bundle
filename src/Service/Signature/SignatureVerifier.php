<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature;

use DateTime;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Header;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\ActorResolverInterface;
use Dontdrinkandroot\Common\Asserted;
use Exception;
use phpseclib3\Crypt\RSA;
use Symfony\Component\HttpFoundation\Request;

class SignatureVerifier implements SignatureVerifierInterface
{
    public function __construct(private readonly ActorResolverInterface $actorService)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function verifyRequest(Request $request): Uri
    {
        $signatureHeader = $request->headers->get(Header::SIGNATURE)
            ?? throw new Exception('Missing Signature Header');

        $this->verifyDateNotExpired($request);
        $this->verifyDigestMatching($request);

        $signatureParts = $this->parseSignatureHeader($signatureHeader);
        $signActorId = $this->getActorIriFromKeyId($signatureParts['keyId']);
        $signActor = $this->actorService->resolve($signActorId);
        if (null === $signActor) {
            throw new Exception('Unknown Actor: ' . $signActorId);
        }
        if (null === ($signActorPublicKeyPem = $signActor->publicKey?->publicKeyPem)) {
            throw new Exception('Actor has no public key: ' . $signActorId);
        }

        // TODO: Make sure all required headers are present
        $signHeaderNames = explode(' ', $signatureParts['headers']);

        $headers = $this->getRequestHeaders($request);
        $headers[Header::REQUEST_TARGET] = sprintf(
            '%s %s',
            strtolower($request->getMethod()),
            $request->getPathInfo()
        );
        $signatureString = SignatureTools::buildSignatureString($signHeaderNames, $headers);

        $signature = base64_decode($signatureParts['signature'], true);

        // TODO: Check algorithm

        $publicKey = Asserted::instanceOf(RSA::loadPublicKey($signActorPublicKeyPem), RSA\PublicKey::class);
        $publicKey = $publicKey->withPadding(RSA::SIGNATURE_PKCS1);

        $verificationResult = $publicKey->verify($signatureString, $signature);
        if (true !== $verificationResult) {
            throw new Exception('Signature Verification Failed');
        }

        return $signActorId;
    }

    private function parseSignatureHeader(string $signatureHeader): array
    {
        // TODO: Improve parsing
        $signatureParts = [];
        $signatureHeaderParts = explode(',', $signatureHeader);
        foreach ($signatureHeaderParts as $signatureHeaderPart) {
            $signatureHeaderPart = trim($signatureHeaderPart);
            $signatureHeaderPartParts = explode('=', $signatureHeaderPart, 2);
            if (2 !== count($signatureHeaderPartParts)) {
                throw new Exception('Invalid Signature Header Part: ' . $signatureHeaderPart);
            }
            $signatureParts[$signatureHeaderPartParts[0]] = trim($signatureHeaderPartParts[1], '"');
        }

        return $signatureParts;
    }

    private function getActorIriFromKeyId(string $keyId): Uri
    {
        $keyIdParts = explode('#', $keyId);
        if (2 !== count($keyIdParts)) {
            throw new Exception('Invalid KeyId: ' . $keyId);
        }

        return Uri::fromString($keyIdParts[0]);
    }

    private function getRequestHeaders(Request $request): array
    {
        // TODO: Review and decide what to do with multi valued headers
        $headers = [];
        foreach ($request->headers->all() as $name => $values) {
            $headers[$name] = $this->getSingleValue($values);
        }

        return $headers;
    }

    /**
     * @param array<int,string|null>|string|null $values
     * @return string
     */
    private function getSingleValue(array|string|null $values): string
    {
        if (is_string($values)) {
            return $values;
        }

        if (null === $values) {
            throw new Exception('Missing Header Value');
        }

        if (1 !== count($values)) {
            throw new Exception('Multiple Header Values');
        }

        $value = $values[0];

        if (null === $value) {
            throw new Exception('Missing Header Value');
        }

        return $value;
    }

    public function verifyDigestMatching(Request $request): void
    {
        $body = Asserted::string($request->getContent());
        $digestHeader = $request->headers->get(Header::DIGEST);
        if (!empty($body) && null === $digestHeader) {
            throw new Exception('Missing Digest Header');
        }
        if (null !== $digestHeader) {
            $digestParts = explode('=', $digestHeader, 2);
            if (2 !== count($digestParts)) {
                throw new Exception('Invalid Digest Header: ' . $digestHeader);
            }
            $digest = $digestParts[1];
            $digestAlgorithm = SignatureTools::resolveDigestAlgorithm($digestParts[0]);
            if (!SignatureTools::verifyDigest($body, $digest, $digestAlgorithm)) {
                throw new Exception('Digest Verification Failed');
            }
        }
    }

    private function verifyDateNotExpired(Request $request): void
    {
        $dateHeader = $request->headers->get(Header::DATE);
        if (null === $dateHeader) {
            throw new Exception('Missing Date Header');
        }
        if (new DateTime($dateHeader) < new DateTime('-12 hours')) {
            throw new Exception('Date Header Expired');
        }
    }
}