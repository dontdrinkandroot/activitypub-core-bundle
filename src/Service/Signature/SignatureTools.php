<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature;

use Exception;

class SignatureTools
{
    /**
     * Maps the internal PHP algorithm names to the display names.
     * @var array<string,string>
     */
    public static array $digestAlgorithms = [
        'sha256' => 'SHA-256',
        'sha512' => 'SHA-512',
    ];

    public static function createRequestTargetHeaderValue(string $method, string $path): string
    {
        return strtolower($method) . ' ' . $path;
    }

    public static function createDigestHeaderValue(string $body, string $algorithm = 'sha256'): string
    {
        return self::$digestAlgorithms[$algorithm] . '=' . self::createDigest($body, $algorithm);
    }

    public static function createDigest(string $body, string $algorithm = 'sha256'): string
    {
        return base64_encode(hash($algorithm, $body, true));
    }

    public static function buildSignatureString(array $signHeaderNames, array $headers): string
    {
        $headers = array_change_key_case($headers);
        $signatureParts = [];
        foreach ($signHeaderNames as $signHeaderName) {
            $headerValue = $headers[$signHeaderName] ?? throw new Exception('Missing Header: ' . $signHeaderName);
            $signatureParts[] = sprintf("%s: %s", $signHeaderName, $headerValue);
        }

        return implode("\n", $signatureParts);
    }

    public static function verifyDigest(string $body, string $digest, string $algorithm = 'sha256'): bool
    {
        return self::createDigest($body, $algorithm) === $digest;
    }

    public static function resolveDigestAlgorithm(string $name): string
    {
        foreach (self::$digestAlgorithms as $internalName => $externalName) {
            if (0 === strcasecmp($name, $externalName)) {
                return $internalName;
            }
        }

        throw new Exception('Unknown Digest Algorithm: ' . $name);
    }
}
