<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Config\Services;

use Dontdrinkandroot\ActivityPubCoreBundle\Config\Container\TagName;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowStorageInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\Handler\AcceptFollowInboxHandler;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\Handler\FollowInboxHandler;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\Handler\InteractionInboxHandler;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\Handler\RejectFollowInboxHandler;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\Handler\UndoFollowInboxHandler;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\InboxServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\SignatureVerifierInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(FollowInboxHandler::class)
        ->args([
            service(LocalActorServiceInterface::class),
            service(FollowServiceInterface::class),
            service(SignatureVerifierInterface::class),
            service(InboxServiceInterface::class)
        ])
        ->tag(TagName::DDR_ACTIVITY_PUB_INBOX_HANDLER);

    $services->set(AcceptFollowInboxHandler::class)
        ->args([
            service(FollowServiceInterface::class),
            service(ObjectResolverInterface::class),
            service(LocalActorServiceInterface::class),
            service(SignatureVerifierInterface::class),
            service(InboxServiceInterface::class)
        ])
        ->tag(TagName::DDR_ACTIVITY_PUB_INBOX_HANDLER);

    $services->set(RejectFollowInboxHandler::class)
        ->args([
            service(FollowServiceInterface::class),
            service(ObjectResolverInterface::class),
            service(LocalActorServiceInterface::class),
            service(SignatureVerifierInterface::class),
            service(InboxServiceInterface::class)
        ])
        ->tag(TagName::DDR_ACTIVITY_PUB_INBOX_HANDLER);

    $services->set(UndoFollowInboxHandler::class)
        ->args([
            service(FollowStorageInterface::class),
            service(ObjectResolverInterface::class),
            service(LocalActorServiceInterface::class),
            service(SignatureVerifierInterface::class),
            service(InboxServiceInterface::class)
        ])
        ->tag(TagName::DDR_ACTIVITY_PUB_INBOX_HANDLER);

    $services->set(InteractionInboxHandler::class)
        ->autowire()
        ->autoconfigure()
        ->tag(TagName::DDR_ACTIVITY_PUB_INBOX_HANDLER);
};
