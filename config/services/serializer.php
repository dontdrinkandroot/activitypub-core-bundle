<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Config\Services;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Container\Tag;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\ActivityStreamEncoder;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\ActivityStreamNormalizer;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\ContextNormalizer;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\DateTimeNormalizer;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\LinkableCollectionNormalizer;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\LinkableNormalizer;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\LinkCollectionNormalizer;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\LinkNormalizer;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\ObjectNormalizer;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\PublicKeyNormalizer;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\ScalarNormalizer;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\SourceNormalizer;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\TypeClassRegistry;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\UriNormalizer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services
        ->set(ActivityStreamEncoder::class)
        ->tag(Tag::SERIALIZER_ENCODER);

    $services
        ->set(ActivityStreamNormalizer::class)
        ->args([service(TypeClassRegistry::class)])
        ->tag(Tag::SERIALIZER_NORMALIZER);

    $services
        ->set(DateTimeNormalizer::class)
        ->tag(Tag::SERIALIZER_NORMALIZER);

    $services
        ->set(PublicKeyNormalizer::class)
        ->tag(Tag::SERIALIZER_NORMALIZER);

    $services
        ->set(UriNormalizer::class)
        ->tag(Tag::SERIALIZER_NORMALIZER);

    $services
        ->set(ScalarNormalizer::class)
        ->tag(Tag::SERIALIZER_NORMALIZER);

    $services
        ->set(LinkCollectionNormalizer::class)
        ->tag(Tag::SERIALIZER_NORMALIZER);

    $services
        ->set(LinkableNormalizer::class)
        ->args([service(TypeClassRegistry::class)])
        ->tag(Tag::SERIALIZER_NORMALIZER);

    $services
        ->set(LinkableCollectionNormalizer::class)
        ->tag(Tag::SERIALIZER_NORMALIZER);

    $services
        ->set(ContextNormalizer::class)
        ->tag(Tag::SERIALIZER_NORMALIZER);

    $services
        ->set(SourceNormalizer::class)
        ->tag(Tag::SERIALIZER_NORMALIZER);

    $services
        ->set(LinkNormalizer::class)
        ->args([service(TypeClassRegistry::class)])
        ->tag(Tag::SERIALIZER_NORMALIZER);

    $services
        ->set(ObjectNormalizer::class)
        ->args([service(TypeClassRegistry::class)])
        ->tag(Tag::SERIALIZER_NORMALIZER, ['priority' => -1]);
};
