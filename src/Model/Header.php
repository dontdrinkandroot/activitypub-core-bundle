<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model;

class Header
{
    public const REQUEST_TARGET = '(request-target)';
    public const DATE = 'Date';
    public const HOST = 'Host';
    public const DIGEST = 'Digest';
    public const CONTENT_TYPE = 'Content-Type';
    public const SIGNATURE = 'Signature';
    public const ACCEPT = 'Accept';
    public const USER_AGENT = 'User-Agent';
    public const ACCEPT_ENCODING = 'Accept-Encoding';
}
