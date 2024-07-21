<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature;

use DateTime;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\ActivityPubRequest;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Header;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignatureVerificationException;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Dontdrinkandroot\Common\Asserted;
use Override;
use phpseclib3\Crypt\RSA;
use Symfony\Component\HttpFoundation\Request;

class SignatureVerifier implements SignatureVerifierInterface
{
    public function __construct(
        private readonly ObjectResolverInterface $objectResolver
    ) {
    }

    #[Override]
    public function verify(ActivityPubRequest $request): Actor
    {
        if ($request->isVerified()) {
            return Asserted::notNull($request->getSignActor());
        }

        $httpRequest = $request->request;
        $signatureHeader = $httpRequest->headers->get(Header::SIGNATURE)
            ?? throw new SignatureVerificationException('Missing Signature Header');

        $this->verifyDateNotExpired($httpRequest);
        $this->verifyDigestMatching($httpRequest);

        $signatureParts = $this->parseSignatureHeader($signatureHeader);
        $keyId = Uri::fromString($signatureParts['keyId']);
        $actorId = $keyId->withFragment(null);

        $actor = $this->objectResolver->resolveTyped($actorId, Actor::class)
            ?? throw new SignatureVerificationException('Could not resolve actor for keyId: ' . $keyId);

        $publicKeyPem = $actor->publicKey?->publicKeyPem
            ?? throw new SignatureVerificationException('Could not resolve public key for keyId: ' . $keyId);

        // TODO: Make sure all required headers are present
        $signHeaderNames = explode(' ', (string)$signatureParts['headers']);

        $headers = $this->getRequestHeaders($httpRequest);
        $headers[Header::REQUEST_TARGET] = sprintf(
            '%s %s',
            strtolower($httpRequest->getMethod()),
            $httpRequest->getPathInfo()
        );
        $signatureString = SignatureTools::buildSignatureString($signHeaderNames, $headers);

        $signature = base64_decode((string)$signatureParts['signature'], true);

        // TODO: Check algorithm

        $publicKey = Asserted::instanceOf(RSA::loadPublicKey($publicKeyPem), RSA\PublicKey::class);
        $publicKey = $publicKey->withPadding(RSA::SIGNATURE_PKCS1);

        $verificationResult = $publicKey->verify($signatureString, $signature);
        if (true !== $verificationResult) {
            throw new SignatureVerificationException('Signature Verification Failed');
        }

        $request->setVerified($actor);

        return $actor;
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
                throw new SignatureVerificationException('Invalid Signature Header Part: ' . $signatureHeaderPart);
            }
            $signatureParts[$signatureHeaderPartParts[0]] = trim($signatureHeaderPartParts[1], '"');
        }

        return $signatureParts;
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
            throw new SignatureVerificationException('Missing Header Value');
        }

        if (1 !== count($values)) {
            throw new SignatureVerificationException('Multiple Header Values');
        }

        $value = $values[0];

        if (null === $value) {
            throw new SignatureVerificationException('Missing Header Value');
        }

        return $value;
    }

    public function verifyDigestMatching(Request $request): void
    {
        $body = $request->getContent();
        $digestHeader = $request->headers->get(Header::DIGEST);
        if (!empty($body) && null === $digestHeader) {
            throw new SignatureVerificationException('Missing Digest Header');
        }
        if (null !== $digestHeader) {
            $digestParts = explode('=', $digestHeader, 2);
            if (2 !== count($digestParts)) {
                throw new SignatureVerificationException('Invalid Digest Header: ' . $digestHeader);
            }
            $digest = $digestParts[1];
            $digestAlgorithm = SignatureTools::resolveDigestAlgorithm($digestParts[0]);
            if (!SignatureTools::verifyDigest($body, $digest, $digestAlgorithm)) {
                throw new SignatureVerificationException('Digest Verification Failed');
            }
        }
    }

    private function verifyDateNotExpired(Request $request): void
    {
        $dateHeader = $request->headers->get(Header::DATE);
        if (null === $dateHeader) {
            throw new SignatureVerificationException('Missing Date Header');
        }
        if (new DateTime($dateHeader) < new DateTime('-12 hours')) {
            throw new SignatureVerificationException('Date Header Expired');
        }
    }
}
