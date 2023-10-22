<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Container;

class Route
{

    public const GET_ACTOR_FOLLOWERS = 'ddr.activitypub.core.followers.get';
    public const POST_ACTOR_INBOX = 'ddr.activitypub.core.inbox.post';
    public const GET_ACTOR = 'ddr.activitypub.core.actor.get';
    public const GET_ACTOR_INBOX = 'ddr.activitypub.core.inbox.get';
    public const POST_ACTOR_OUTBOX = 'ddr.activitypub.core.outbox.post';
    public const GET_ACTOR_OUTBOX = 'ddr.activitypub.core.outbox.get';
    public const GET_ACTOR_FOLLOWING = 'ddr.activitypub.core.following.get';
}
