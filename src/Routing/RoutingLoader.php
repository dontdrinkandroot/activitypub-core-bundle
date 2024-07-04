<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Routing;

use Dontdrinkandroot\ActivityPubCoreBundle\Config\Route\RequestAttribute;
use Dontdrinkandroot\ActivityPubCoreBundle\Config\Route\RouteName as RouteName;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\FollowersAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\FollowingAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\GetAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\Inbox\GetAction as InboxGetAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\Inbox\PostAction as InboxPostAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\Outbox\GetAction as OutboxGetAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\Outbox\PostAction as OutboxPostAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\SharedInboxAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\WebfingerAction;
use Override;
use RuntimeException;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RoutingLoader extends Loader
{
    public const string TYPE = 'ddr_activitypub_core';

    private bool $loaded = false;

    public function __construct(private readonly string $actorPathPrefix)
    {
        parent::__construct();
    }

    #[Override]
    public function supports(mixed $resource, string $type = null): bool
    {
        return self::TYPE === $type;
    }

    #[Override]
    public function load(mixed $resource, string $type = null): RouteCollection
    {
        if (true === $this->loaded) {
            throw new RuntimeException(sprintf("Do not add the \"%s\" loader twice", self::TYPE));
        }

        $routes = new RouteCollection();

        $route = new Route(
            path: '/.well-known/webfinger',
            defaults: [
                RequestAttribute::CONTROLLER => WebfingerAction::class,
            ],
            methods: [Request::METHOD_GET],
        );
        $routes->add(RouteName::WEBFINGER, $route);

        $route = new Route(
            path: '/inbox',
            defaults: [
                RequestAttribute::CONTROLLER => SharedInboxAction::class
            ],
            methods: [Request::METHOD_POST],
        );
        $routes->add(RouteName::POST_SHARED_INBOX, $route);

        $actorPathPrefix = $this->actorPathPrefix . '{username}';

        $route = new Route(
            $actorPathPrefix . '/inbox',
            defaults: [
                RequestAttribute::CONTROLLER => InboxGetAction::class,
            ],
            methods: [Request::METHOD_GET],
        );
        $routes->add(RouteName::GET_ACTOR_INBOX, $route);

        $route = new Route(
            path: $actorPathPrefix . '/inbox',
            defaults: [
                RequestAttribute::CONTROLLER => InboxPostAction::class,
            ],
            methods: [Request::METHOD_POST],
        );
        $routes->add(RouteName::POST_ACTOR_INBOX, $route);

        $route = new Route(
            path: $actorPathPrefix . '/outbox',
            defaults: [
                RequestAttribute::CONTROLLER => OutboxGetAction::class,
            ],
            methods: [Request::METHOD_GET],
        );
        $routes->add(RouteName::GET_ACTOR_OUTBOX, $route);

        $route = new Route(
            path: $actorPathPrefix . '/outbox',
            defaults: [
                RequestAttribute::CONTROLLER => OutboxPostAction::class,
            ],
            methods: [Request::METHOD_POST],
        );
        $routes->add(RouteName::POST_ACTOR_OUTBOX, $route);

        $route = new Route(
            path: $actorPathPrefix . '/followers',
            defaults: [
                RequestAttribute::CONTROLLER => FollowersAction::class,
            ],
            methods: [Request::METHOD_GET],
        );
        $routes->add(RouteName::GET_ACTOR_FOLLOWERS, $route);

        $route = new Route(
            path: $actorPathPrefix . '/following',
            defaults: [
                RequestAttribute::CONTROLLER => FollowingAction::class,
            ],
            methods: [Request::METHOD_GET],
        );
        $routes->add(RouteName::GET_ACTOR_FOLLOWING, $route);

        $route = new Route(
            $actorPathPrefix,
            defaults: [
                RequestAttribute::CONTROLLER => GetAction::class,
            ],
            methods: [Request::METHOD_GET],
        );
        $routes->add(RouteName::GET_ACTOR, $route);

        $this->loaded = true;

        return $routes;
    }
}
