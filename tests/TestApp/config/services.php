<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Config;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Container\TagName;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Delivery\DeliveryServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowService;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowStorageInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolver;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Share\InteractionServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Service\DeliveryService;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Service\FollowStorage;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Service\HttpClient\KernelBrowserHttpClient;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Service\InteractionService;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Service\LocalActorService;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Service\Object\MockObjectProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->load('Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Service\\', '../Service/*')
        ->autowire()
        ->autoconfigure();

    $services->set(KernelBrowserHttpClient::class)
        ->args([
            service('test.client')
        ]);

    $services->alias(HttpClientInterface::class, KernelBrowserHttpClient::class);
    $services->set(MockObjectProvider::class)
        ->tag(TagName::OBJECT_PROVIDER)
        ->public();

    $services->alias(LocalActorServiceInterface::class, LocalActorService::class);
    $services->alias(FollowStorageInterface::class, FollowStorage::class)->public();
    $services->alias(DeliveryServiceInterface::class, DeliveryService::class);
    $services->alias(InteractionServiceInterface::class, InteractionService::class)->public();
    $services->alias(FollowServiceInterface::class, FollowService::class)->public();
    $services->alias(ObjectResolverInterface::class, ObjectResolver::class)->public();
};
