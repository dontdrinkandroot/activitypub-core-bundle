<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Serializer;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class ActivityStreamEncoder implements EncoderInterface, DecoderInterface
{
    public const FORMAT = 'activity-stream';
    const ACTIVITY_STREAM_NS = 'https://www.w3.org/ns/activitystreams';

    /**
     * {@inheritdoc}
     */
    public function supportsDecoding(string $format): bool
    {
        return self::FORMAT === $format;
    }

    /**
     * {@inheritdoc}
     */
    public function decode(string $data, string $format, array $context = []): object
    {
        return json_decode($data, false, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsEncoding(string $format): bool
    {
        return self::FORMAT === $format;
    }

    /**
     * {@inheritdoc}
     */
    public function encode(mixed $data, string $format, array $context = []): string
    {
        assert(is_array($data));
        $data = $this->addNamespaceIfMissing($data);

        return json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function addNamespaceIfMissing(array $data): array
    {
        if (!isset($data['@context'])) {
            $data = ['@context' => self::ACTIVITY_STREAM_NS] + $data;
        }

        $jsonLdContext = $data['@context'];
        if (is_string($jsonLdContext) && self::ACTIVITY_STREAM_NS !== $jsonLdContext) {
            $data['@context'] = [$jsonLdContext, self::ACTIVITY_STREAM_NS];
        } elseif (is_array($jsonLdContext) && !in_array(self::ACTIVITY_STREAM_NS, $jsonLdContext)) {
            $data['@context'] = array_merge($jsonLdContext, [self::ACTIVITY_STREAM_NS]);
        }
        return $data;
    }
}
