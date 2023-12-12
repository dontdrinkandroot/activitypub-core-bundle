<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Config\Services;

use Dontdrinkandroot\ActivityPubCoreBundle\Event\InboxEvent;
use Dontdrinkandroot\ActivityPubCoreBundle\Event\Listener\Inbox\AcceptFollowListener;
use Dontdrinkandroot\ActivityPubCoreBundle\Event\Listener\Inbox\FollowListener;
use Dontdrinkandroot\ActivityPubCoreBundle\Event\Listener\Inbox\InteractionListener;
use Dontdrinkandroot\ActivityPubCoreBundle\Event\Listener\Inbox\RejectFollowListener;
use Dontdrinkandroot\ActivityPubCoreBundle\Event\Listener\Inbox\UndoFollowInboxHandler;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Container\TagName;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowStorageInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(FollowListener::class)
        ->args([
            service(LocalActorServiceInterface::class),
            service(FollowServiceInterface::class)
        ])
        ->tag(TagName::KERNEL_EVENT_LISTENER, ['event' => InboxEvent::class]);

    $services->set(AcceptFollowListener::class)
        ->args([
            service(FollowServiceInterface::class),
            service(ObjectResolverInterface::class),
            service(LocalActorServiceInterface::class)
        ])
        ->tag(TagName::KERNEL_EVENT_LISTENER, ['event' => InboxEvent::class]);

    $services->set(RejectFollowListener::class)
        ->args([
            service(FollowServiceInterface::class),
            service(ObjectResolverInterface::class),
            service(LocalActorServiceInterface::class)
        ])
        ->tag(TagName::KERNEL_EVENT_LISTENER, ['event' => InboxEvent::class]);

    $services->set(UndoFollowInboxHandler::class)
        ->args([
            service(FollowStorageInterface::class),
            service(ObjectResolverInterface::class),
            service(LocalActorServiceInterface::class)
        ])
        ->tag(TagName::KERNEL_EVENT_LISTENER, ['event' => InboxEvent::class]);

    $services->set(InteractionListener::class)
        ->autowire()
        ->autoconfigure()
        ->tag(TagName::KERNEL_EVENT_LISTENER, ['event' => InboxEvent::class]);
};
