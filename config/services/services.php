<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Config\Services;

use Dontdrinkandroot\ActivityPubCoreBundle\Config\Container\ParamName;
use Dontdrinkandroot\ActivityPubCoreBundle\Config\Container\TagName;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\FollowersAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\FollowingAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\GetAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\Inbox\GetAction as InboxGetAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\Inbox\PostAction as InboxPostAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\Outbox\GetAction as OutboxGetAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\Outbox\PostAction as OutboxPostAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\SharedInboxAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\WebfingerAction;
use Dontdrinkandroot\ActivityPubCoreBundle\DataCollector\ActivityPubDataCollector;
use Dontdrinkandroot\ActivityPubCoreBundle\Event\Listener\ResponseForFormatListener;
use Dontdrinkandroot\ActivityPubCoreBundle\Routing\RoutingLoader;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\TypeClassRegistry;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorPopulator;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorUriGenerator;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorUriGeneratorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClient;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Delivery\DeliveryServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowService;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowStorageInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\FetchingObjectProvider;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolver;
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

    $services->set(RoutingLoader::class)
        ->args([param(ParamName::ACTOR_PATH_PREFIX)])
        ->tag(TagName::ROUTING_LOADER);

    $services->set(TypeClassRegistry::class);

    $services->set(SignatureGenerator::class);
    $services->alias(SignatureGeneratorInterface::class, SignatureGenerator::class);

    $services->set(SignatureVerifier::class)#
    ->args([
        service(ObjectResolverInterface::class)
    ]);
    $services->alias(SignatureVerifierInterface::class, SignatureVerifier::class);

    $services->set(ActivityPubClient::class)
        ->args([
            service(SerializerInterface::class),
            service(SignatureGeneratorInterface::class),
            service(HttpClientInterface::class)
        ]);
    $services->alias(ActivityPubClientInterface::class, ActivityPubClient::class);

    $services->set(KeyPairGenerator::class);
    $services->alias(KeyPairGeneratorInterface::class, KeyPairGenerator::class);

    $services->set(FollowService::class)
        ->args([
            service(FollowStorageInterface::class),
            service(DeliveryServiceInterface::class),
            service(ObjectResolverInterface::class),
            service(LocalActorUriGeneratorInterface::class),
            param(ParamName::FOLLOW_RESPONSE_MODE)
        ]);
    $services->alias(FollowServiceInterface::class, FollowService::class);

    $services->set(LocalActorUriGenerator::class)
        ->args([
            service(UrlGeneratorInterface::class),
            service(UrlMatcherInterface::class),
            param(ParamName::HOST)
        ]);
    $services->alias(LocalActorUriGeneratorInterface::class, LocalActorUriGenerator::class);

    $services->set(LocalActorPopulator::class)
        ->args([
            service(LocalActorUriGeneratorInterface::class),
            service(TypeClassRegistry::class)
        ]);

    $services->set(CachedWebFingerService::class)
        ->args([
            service(HttpClientInterface::class),
            service('cache.app')
        ]);
    $services->alias(WebFingerServiceInterface::class, CachedWebFingerService::class);

    $services->set(FetchingObjectProvider::class)
        ->args([
            service(ActivityPubClientInterface::class),
        ])
        ->tag(TagName::DDR_ACTIVITY_PUB_OBJECT_PROVIDER, ['priority' => -256]);

    $services->set(ObjectResolver::class)
        ->args([
            tagged_iterator(TagName::DDR_ACTIVITY_PUB_OBJECT_PROVIDER)
        ]);
    $services->alias(ObjectResolverInterface::class, ObjectResolver::class);

    $services->set(ResponseForFormatListener::class)
        ->args([
            service(SerializerInterface::class),
        ])
        ->tag(TagName::KERNEL_EVENT_LISTENER, ['event' => 'kernel.view', 'method' => 'onView']);

    /*
     * Controllers
     */
    $services
        ->set(WebfingerAction::class)
        ->autowire()
        ->autoconfigure()
        ->tag(TagName::CONTROLLER);

    $services
        ->set(SharedInboxAction::class)
        ->autowire()
        ->autoconfigure()
        ->arg('$inboxHandlers', tagged_iterator(TagName::DDR_ACTIVITY_PUB_INBOX_HANDLER))
        ->tag(TagName::CONTROLLER)
        ->tag(TagName::MONOLOG_LOGGER, ['channel' => 'activitypub']);

    $services
        ->set(GetAction::class)
        ->autowire()
        ->autoconfigure()
        ->tag(TagName::CONTROLLER);

    $services
        ->set(InboxGetAction::class)
        ->autowire()
        ->autoconfigure()
        ->tag(TagName::CONTROLLER);

    $services
        ->set(InboxPostAction::class)
        ->autowire()
        ->autoconfigure()
        ->arg('$inboxHandlers', tagged_iterator(TagName::DDR_ACTIVITY_PUB_INBOX_HANDLER))
        ->tag(TagName::CONTROLLER)
        ->tag(TagName::MONOLOG_LOGGER, ['channel' => 'activitypub']);

    $services
        ->set(OutboxGetAction::class)
        ->autowire()
        ->autoconfigure()
        ->tag(TagName::CONTROLLER);

    $services
        ->set(OutboxPostAction::class)
        ->autowire()
        ->autoconfigure()
        ->tag(TagName::CONTROLLER);

    $services
        ->set(FollowersAction::class)
        ->autowire()
        ->autoconfigure()
        ->tag(TagName::CONTROLLER);

    $services
        ->set(FollowingAction::class)
        ->autowire()
        ->autoconfigure()
        ->tag(TagName::CONTROLLER);

    $services
        ->set(ActivityPubDataCollector::class)
        ->tag(TagName::DATA_COLLECTOR, ['id' => ActivityPubDataCollector::class]);
};
