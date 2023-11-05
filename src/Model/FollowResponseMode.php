<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model;

enum FollowResponseMode: string
{
    case ACCEPT = 'accept';
    case REJECT = 'reject';
    case MANUAL = 'manual';
}
