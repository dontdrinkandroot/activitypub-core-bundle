<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Config\Route;

class RouteName
{
    public const string DDR_ACTIVITYPUB_PREFIX = 'ddr.activity_pub.';
    public const string CORE_PREFIX = self::DDR_ACTIVITYPUB_PREFIX . 'core.';

    public const string WEBFINGER = self::CORE_PREFIX . 'webfinger';
    public const string POST_SHARED_INBOX = self::CORE_PREFIX . 'shared_inbox.post';
    public const string GET_ACTOR = self::CORE_PREFIX . 'actor.get';
    public const string GET_ACTOR_FOLLOWERS = self::CORE_PREFIX . 'actor.followers.get';
    public const string GET_ACTOR_FOLLOWING = self::CORE_PREFIX . 'actor.following.get';
    public const string POST_ACTOR_INBOX = self::CORE_PREFIX . 'actor.inbox.post';
    public const string GET_ACTOR_INBOX = self::CORE_PREFIX . 'actor.inbox.get';
    public const string POST_ACTOR_OUTBOX = self::CORE_PREFIX . 'actor.outbox.post';
    public const string GET_ACTOR_OUTBOX = self::CORE_PREFIX . 'actor.outbox.get';
}
