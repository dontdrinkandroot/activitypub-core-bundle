<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Container;

class Route
{
    public const DDR_ACTIVITYPUB_PREFIX = 'ddr.activity_pub.';
    public const CORE_PREFIX = self::DDR_ACTIVITYPUB_PREFIX . 'core.';

    public const WEBFINGER = self::CORE_PREFIX . 'webfinger';
    public const POST_SHARED_INBOX = self::CORE_PREFIX . 'shared_inbox.post';
    public const GET_ACTOR = self::CORE_PREFIX . 'actor.get';
    public const GET_ACTOR_FOLLOWERS = self::CORE_PREFIX . 'actor.followers.get';
    public const GET_ACTOR_FOLLOWING = self::CORE_PREFIX . 'actor.following.get';
    public const POST_ACTOR_INBOX = self::CORE_PREFIX . 'actor.inbox.post';
    public const GET_ACTOR_INBOX = self::CORE_PREFIX . 'actor.inbox.get';
    public const POST_ACTOR_OUTBOX = self::CORE_PREFIX . 'actor.outbox.post';
    public const GET_ACTOR_OUTBOX = self::CORE_PREFIX . 'actor.outbox.get';
}
