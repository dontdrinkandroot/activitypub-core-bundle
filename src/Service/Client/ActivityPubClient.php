<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Client;

use DateTimeImmutable;
use DateTimeInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Header;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\ActivityStreamEncoder;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureGeneratorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureTools;
use Dontdrinkandroot\Common\Asserted;
use JsonException;
use RuntimeException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

class ActivityPubClient implements ActivityPubClientInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly SignatureGeneratorInterface $signatureGenerator,
        private readonly HttpClientInterface $httpClient,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function request(
        string $method,
        Uri $uri,
        CoreType|string|null $content = null,
        ?SignKey $signKey = null
    ): ?CoreObject {
        $host = Asserted::notNull($uri->getAuthority());
        $path = Asserted::notNull($uri->getPathWithQueryAndFragment());

        $body = $this->contentToBody($content);

        $headers = $this->createHeaders($method, $host, $path, $body, $signKey);

        $response = $this->httpClient->request($method, (string)$uri, [
            'verify_peer' => false, // TODO: Remove,
            'verify_host' => false, // TODO: Remove,
            'headers' => $headers,
            'body' => $body
        ]);

        try {
            $content = $response->getContent();
            if (empty($content)) {
                return null;
            }

            return Asserted::instanceOf(
                $this->serializer->deserialize($content, CoreType::class, ActivityStreamEncoder::FORMAT),
                CoreObject::class
            );
        } catch (ClientExceptionInterface|ServerExceptionInterface $e) {
            $content = $this->formatJsonContent($response);
            throw new RuntimeException($content, $response->getStatusCode(), $e);
        } catch (Throwable $e) {
            throw $e;
        }
    }

    protected function createHeaders(
        string $method,
        string $host,
        string $path,
        ?string $body,
        ?SignKey $signKey = null
    ): array {
        $headers = [
            Header::HOST => $host,
            Header::DATE => (new DateTimeImmutable())->format(DateTimeInterface::RFC7231),
            Header::ACCEPT => 'application/activity+json',
            Header::USER_AGENT => 'dontdrinkandroot/activity-pub-core-bundle'
        ];

        if (null !== $body) {
            $headers[Header::CONTENT_TYPE] = 'application/activity+json';
            $headers[Header::DIGEST] = SignatureTools::createDigestHeaderValue($body);
        }

        if (null !== $signKey) {
            $headers[Header::SIGNATURE] = $this->signatureGenerator
                ->generateSignatureHeader($method, $path, $signKey, $headers);
        }

        return $headers;
    }

    public function formatJsonContent(ResponseInterface $response): string
    {
        $content = $response->getContent(false);
        try {
            return Asserted::string(
                json_encode(
                    json_decode($content, false, 512, JSON_THROW_ON_ERROR),
                    JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
                )
            );
        } catch (JsonException $e) {
            return $content;
        }
    }

    public function contentToBody(CoreType|string|null $content): ?string
    {
        if (null === $content) {
            return null;
        }

        if (is_string($content)) {
            return $content;
        }

        return $this->serializer->serialize($content, ActivityStreamEncoder::FORMAT);
    }
}
