<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\WebFinger;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Override;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WebFingerService implements WebFingerServiceInterface
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
    ) {
    }

    #[Override]
    public function resolveIri(string $username, string $domain): ?Uri
    {
        $response = $this->httpClient->request(
            'GET',
            sprintf('https://%s/.well-known/webfinger?resource=acct:%s@%s', $domain, $username, $domain),
            [
                'verify_peer' => false, // TODO: Remove,
                'verify_host' => false, // TODO: Remove,
            ]
        );

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        $content = $response->toArray();

        foreach ($content['links'] as $link) {
            if ($link['rel'] === 'self') {
                return Uri::fromString($link['href']);
            }
        }

        return null;
    }
}
