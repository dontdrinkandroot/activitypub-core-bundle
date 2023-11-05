<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model;

enum FollowResponseType: string
{
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
}
