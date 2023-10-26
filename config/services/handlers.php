<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Config\Services;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Container\Tag;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowStorageInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\AcceptFollowInboxHandler;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\AnnounceInboxHandler;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\FollowInboxHandler;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\RejectFollowInboxHandler;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\UndoFollowInboxHandler;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(FollowInboxHandler::class)
        ->args([
            service(LocalActorServiceInterface::class),
            service(FollowStorageInterface::class)
        ])
        ->tag(Tag::INBOX_HANDLER);

    $services->set(AcceptFollowInboxHandler::class)
        ->args([
            service(FollowStorageInterface::class),
            service(ObjectResolverInterface::class),
            service(LocalActorServiceInterface::class)
        ])
        ->tag(Tag::INBOX_HANDLER);

    $services->set(RejectFollowInboxHandler::class)
        ->args([
            service(FollowStorageInterface::class),
            service(ObjectResolverInterface::class),
            service(LocalActorServiceInterface::class)
        ])
        ->tag(Tag::INBOX_HANDLER);

    $services->set(UndoFollowInboxHandler::class)
        ->args([
            service(FollowStorageInterface::class),
            service(ObjectResolverInterface::class),
            service(LocalActorServiceInterface::class)
        ])
        ->tag(Tag::INBOX_HANDLER);

    $services->set(AnnounceInboxHandler::class)
        ->autowire()
        ->autoconfigure()
        ->tag(Tag::INBOX_HANDLER);
};
