<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Serializer;

use Dontdrinkandroot\Common\Asserted;
use RuntimeException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @psalm-requires-implements SerializerAwareInterface
 */
trait SerializerAwareTrait
{
    protected ?Serializer $serializer = null;

    /**
     * {@inheritdoc}
     */
    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = Asserted::instanceOf($serializer, Serializer::class);
    }

    public function getSerializer(): Serializer
    {
        return $this->serializer ?? throw new RuntimeException('Serializer not set');
    }
}
