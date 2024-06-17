<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model;

class Header
{
    public const string REQUEST_TARGET = '(request-target)';
    public const string DATE = 'Date';
    public const string HOST = 'Host';
    public const string  DIGEST = 'Digest';
    public const string CONTENT_TYPE = 'Content-Type';
    public const string SIGNATURE = 'Signature';
    public const string ACCEPT = 'Accept';
    public const string USER_AGENT = 'User-Agent';
    public const string ACCEPT_ENCODING = 'Accept-Encoding';
}
