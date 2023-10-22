<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\ActivityStreamEncoder;
use Symfony\Component\Serializer\SerializerInterface;

trait SerializationTestTrait
{
    protected function restoreJson(string $json): array
    {
        $serializer = self::getService(SerializerInterface::class);

        return json_decode(
            $serializer->serialize(
                $serializer->deserialize($json, CoreType::class, ActivityStreamEncoder::FORMAT),
                ActivityStreamEncoder::FORMAT
            ),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }
}
