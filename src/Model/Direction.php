<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model;

enum Direction: int
{
    case INCOMING = 0;
    case OUTGOING = 1;
}
