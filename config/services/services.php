<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Config\Services;

use Dontdrinkandroot\ActivityPubCoreBundle\Controller\ActorAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\FollowersAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Inbox\GetAction as InboxGetAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Inbox\PostAction as InboxPostAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Outbox\GetAction as OutboxGetAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Outbox\PostAction as OutboxPostAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\WebfingerAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Event\Listener\ResponseForFormatListener;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Container\Param;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Container\Tag;
use Dontdrinkandroot\ActivityPubCoreBundle\Routing\RoutingLoader;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\TypeClassRegistry;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\ActorResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\FetchingActorResolver;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorUriGenerator;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorUriGeneratorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClient;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Delivery\DeliveryServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowerStorageInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowingStorageInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowService;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\FetchingObjectResolver;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\KeyPairGenerator;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\KeyPairGeneratorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureGenerator;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureGeneratorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureVerifier;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureVerifierInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\WebFinger\CachedWebFingerService;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\WebFinger\WebFingerServiceInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services
        ->set(RoutingLoader::class)
        ->args([param(Param::ACTOR_PATH_PREFIX)])
        ->tag(Tag::ROUTING_LOADER);

    $services
        ->set(TypeClassRegistry::class);

    $services
        ->set(SignatureGenerator::class);

    $services
        ->alias(SignatureGeneratorInterface::class, SignatureGenerator::class);

    $services
        ->set(SignatureVerifier::class)#
        ->args([service(ActorResolverInterface::class)]);

    $services
        ->alias(SignatureVerifierInterface::class, SignatureVerifier::class);

    $services
        ->set(ActivityPubClient::class)
        ->args([
            service(SerializerInterface::class),
            service(SignatureGeneratorInterface::class),
            service(HttpClientInterface::class)
        ]);

    $services
        ->alias(ActivityPubClientInterface::class, ActivityPubClient::class);

    $services
        ->set(KeyPairGenerator::class);

    $services
        ->alias(KeyPairGeneratorInterface::class, KeyPairGenerator::class);

    $services->set(FollowService::class)
        ->args([
            service(FollowingStorageInterface::class),
            service(FollowerStorageInterface::class),
            service(DeliveryServiceInterface::class),
            service(ActorResolverInterface::class),
            service(LocalActorUriGeneratorInterface::class)
        ]);

    $services->alias(FollowServiceInterface::class, FollowService::class);

    $services->set(FetchingActorResolver::class)
        ->args([
            service(ActivityPubClientInterface::class),
        ]);
    $services->alias(ActorResolverInterface::class, FetchingActorResolver::class);

    $services->set(LocalActorUriGenerator::class)
        ->args([
            service(UrlGeneratorInterface::class),
            service(UrlMatcherInterface::class)
        ]);
    $services->alias(LocalActorUriGeneratorInterface::class, LocalActorUriGenerator::class);

    $services->set(CachedWebFingerService::class)
        ->args([
            service(HttpClientInterface::class),
            service('cache.app')
        ]);
    $services->alias(WebFingerServiceInterface::class, CachedWebFingerService::class);

    $services->set(FetchingObjectResolver::class)
        ->args([
            service(ActivityPubClientInterface::class)
        ]);
    $services->alias(ObjectResolverInterface::class, FetchingObjectResolver::class);

    $services->set(ResponseForFormatListener::class)
        ->args([
            service(SerializerInterface::class),
        ])
        ->tag(Tag::KERNEL_EVENT_LISTENER, ['event' => 'kernel.view', 'method' => 'onView']);

    /*
     * Controllers
     */
    $services
        ->set(WebfingerAction::class)
        ->autowire()
        ->autoconfigure()
        ->tag(Tag::CONTROLLER);

    $services
        ->set(ActorAction::class)
        ->autowire()
        ->autoconfigure()
        ->tag(Tag::CONTROLLER);

    $services
        ->set(InboxGetAction::class)
        ->autowire()
        ->autoconfigure()
        ->tag(Tag::CONTROLLER);

    $services
        ->set(InboxPostAction::class)
        ->arg('$handlers', tagged_iterator(Tag::INBOX_HANDLER))
        ->autowire()
        ->autoconfigure()
        ->tag(Tag::CONTROLLER);

    $services
        ->set(OutboxGetAction::class)
        ->autowire()
        ->autoconfigure()
        ->tag(Tag::CONTROLLER);

    $services
        ->set(OutboxPostAction::class)
        ->autowire()
        ->autoconfigure()
        ->tag(Tag::CONTROLLER);

    $services
        ->set(FollowersAction::class)
        ->autowire()
        ->autoconfigure()
        ->tag(Tag::CONTROLLER);
};
