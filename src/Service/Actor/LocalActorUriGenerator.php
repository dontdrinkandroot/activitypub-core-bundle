<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Container\RouteName;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\Common\Asserted;
use Override;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class LocalActorUriGenerator implements LocalActorUriGeneratorInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly UrlMatcherInterface $urlMatcher,
        private readonly string $host,
    ) {
    }

    #[Override]
    public function generateId(LocalActorInterface|string $usernameOrLocalActor): Uri
    {
        $this->urlMatcher->getContext()->setHost($this->host);
        $this->urlMatcher->getContext()->setScheme('https');
        $username = $this->getUsername($usernameOrLocalActor);
        return Uri::fromString(
            $this->urlGenerator->generate(
                RouteName::GET_ACTOR,
                ['username' => $username],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
    }

    #[Override]
    public function generateSharedInbox(): Uri
    {
        $this->urlMatcher->getContext()->setHost($this->host);
        $this->urlMatcher->getContext()->setScheme('https');
        return Uri::fromString(
            $this->urlGenerator->generate(
                RouteName::POST_SHARED_INBOX,
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
    }

    #[Override]
    public function generateInbox(LocalActorInterface|string $usernameOrLocalActor): Uri
    {
        $this->urlMatcher->getContext()->setHost($this->host);
        $this->urlMatcher->getContext()->setScheme('https');
        $username = $this->getUsername($usernameOrLocalActor);
        return Uri::fromString(
            $this->urlGenerator->generate(
                RouteName::GET_ACTOR_INBOX,
                ['username' => $username],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
    }

    #[Override]
    public function generateOutbox(LocalActorInterface|string $usernameOrLocalActor): Uri
    {
        $this->urlMatcher->getContext()->setHost($this->host);
        $this->urlMatcher->getContext()->setScheme('https');
        $username = $this->getUsername($usernameOrLocalActor);
        return Uri::fromString(
            $this->urlGenerator->generate(
                RouteName::GET_ACTOR_OUTBOX,
                ['username' => $username],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
    }

    #[Override]
    public function generateFollowers(LocalActorInterface|string $usernameOrLocalActor, ?int $page = null): Uri
    {
        $this->urlMatcher->getContext()->setHost($this->host);
        $this->urlMatcher->getContext()->setScheme('https');
        $username = $this->getUsername($usernameOrLocalActor);
        $parameters = ['username' => $username];
        if (null !== $page) {
            $parameters['page'] = $page;
        }
        return Uri::fromString(
            $this->urlGenerator->generate(
                RouteName::GET_ACTOR_FOLLOWERS,
                $parameters,
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
    }

    #[Override]
    public function generateFollowing(LocalActorInterface|string $usernameOrLocalActor, ?int $page = null): Uri
    {
        $this->urlMatcher->getContext()->setHost($this->host);
        $this->urlMatcher->getContext()->setScheme('https');
        $username = $this->getUsername($usernameOrLocalActor);
        $parameters = ['username' => $username];
        if (null !== $page) {
            $parameters['page'] = $page;
        }
        return Uri::fromString(
            $this->urlGenerator->generate(
                RouteName::GET_ACTOR_FOLLOWING,
                $parameters,
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
    }

    #[Override]
    public function matchUsername(Uri $uri): ?string
    {
        if ($uri->host !== $this->host) {
            return null;
        }

        $this->urlMatcher->getContext()->setMethod('GET');

        try {
            $parameters = $this->urlMatcher->match(Asserted::notNull($uri->getPathWithQueryAndFragment()));
        } catch (ResourceNotFoundException) {
            return null;
        }

        if (RouteName::GET_ACTOR !== ($parameters['_route'] ?? null)) {
            return null;
        }

        return $parameters['username'] ?? null;
    }

    private function getUsername(LocalActorInterface|string $usernameOrLocalActor): string
    {
        if (is_string($usernameOrLocalActor)) {
            return $usernameOrLocalActor;
        }
        return $usernameOrLocalActor->getUsername();
    }

}
