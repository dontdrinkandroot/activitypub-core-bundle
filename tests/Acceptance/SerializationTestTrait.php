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

        $coreType = $serializer->deserialize($json, CoreType::class, ActivityStreamEncoder::FORMAT);
        $restoredJson = $serializer->serialize($coreType, ActivityStreamEncoder::FORMAT);

        return json_decode($restoredJson, true, 512, JSON_THROW_ON_ERROR);
    }
}
