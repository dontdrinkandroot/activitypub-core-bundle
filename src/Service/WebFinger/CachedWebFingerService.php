<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\WebFinger;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CachedWebFingerService extends WebFingerService
{
    public function __construct(HttpClientInterface $httpClient, private readonly CacheInterface $cache)
    {
        parent::__construct($httpClient);
    }

    /**
     * {@inheritdoc}
     */
    public function resolveIri(string $username, string $domain): ?Uri
    {
        return $this->cache->get(
            sprintf('webfinger.%s-at-%s', $username, $domain),
            function (ItemInterface $item) use ($username, $domain) {
                $item->expiresAfter(86400);
                return parent::resolveIri($username, $domain);
            }
        );
    }
}
