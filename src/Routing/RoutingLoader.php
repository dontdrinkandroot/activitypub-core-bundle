<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Routing;

use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\GetAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\FollowersAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\FollowingAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\Inbox\GetAction as InboxGetAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\Inbox\PostAction as InboxPostAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\Outbox\GetAction as OutboxGetAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\Actor\Outbox\PostAction as OutboxPostAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\SharedInboxAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Controller\WebfingerAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Container\Route as RouteName;
use RuntimeException;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RoutingLoader extends Loader
{

    private bool $loaded = false;

    public function __construct(private readonly string $actorPathPrefix)
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function supports(mixed $resource, string $type = null): bool
    {
        return 'ddr_activitypub_core' === $type;
    }

    /**
     * {@inheritdoc}
     */
    public function load(mixed $resource, string $type = null): RouteCollection
    {
        if (true === $this->loaded) {
            throw new RuntimeException('Do not add the "ddr_activitypub_core" loader twice');
        }

        $routes = new RouteCollection();

        $route = new Route(
            path: '/.well-known/webfinger',
            defaults: [
                '_controller' => WebfingerAction::class,
            ],
            methods: ['GET'],
        );
        $routes->add(RouteName::WEBFINGER, $route);

        $route = new Route(
            path: '/inbox',
            defaults: [
                '_controller' => SharedInboxAction::class
            ],
            methods: ['POST'],
        );
        $routes->add(RouteName::POST_SHARED_INBOX, $route);

        $actorPathPrefix = $this->actorPathPrefix . '{username}';

        $route = new Route(
            $actorPathPrefix . '/inbox',
            defaults: [
                '_controller' => InboxGetAction::class,
            ],
            methods: ['GET'],
        );
        $routes->add(RouteName::GET_ACTOR_INBOX, $route);

        $route = new Route(
            path: $actorPathPrefix . '/inbox',
            defaults: [
                '_controller' => InboxPostAction::class,
            ],
            methods: ['POST'],
        );
        $routes->add(RouteName::POST_ACTOR_INBOX, $route);

        $route = new Route(
            path: $actorPathPrefix . '/outbox',
            defaults: [
                '_controller' => OutboxGetAction::class,
            ],
            methods: ['GET'],
        );
        $routes->add(RouteName::GET_ACTOR_OUTBOX, $route);

        $route = new Route(
            path: $actorPathPrefix . '/outbox',
            defaults: [
                '_controller' => OutboxPostAction::class,
            ],
            methods: ['POST'],
        );
        $routes->add(RouteName::POST_ACTOR_OUTBOX, $route);

        $route = new Route(
            path: $actorPathPrefix . '/followers',
            defaults: [
                '_controller' => FollowersAction::class,
            ],
            methods: ['GET'],
        );
        $routes->add(RouteName::GET_ACTOR_FOLLOWERS, $route);

        $route = new Route(
            path: $actorPathPrefix . '/following',
            defaults: [
                '_controller' => FollowingAction::class,
            ],
            methods: ['GET'],
        );
        $routes->add(RouteName::GET_ACTOR_FOLLOWING, $route);

        $route = new Route(
            $actorPathPrefix,
            defaults: [
                '_controller' => GetAction::class,
            ],
            methods: ['GET'],
        );
        $routes->add(RouteName::GET_ACTOR, $route);

        $this->loaded = true;

        return $routes;
    }
}
