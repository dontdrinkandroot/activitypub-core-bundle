<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\Common\Asserted;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class LocalActorUriGenerator implements LocalActorUriGeneratorInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly UrlMatcherInterface $urlMatcher
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function generateId(LocalActorInterface|string $usernameOrLocalActor): Uri
    {
        $username = $this->getUsername($usernameOrLocalActor);
        return Uri::fromString(
            $this->urlGenerator->generate(
                'ddr.activitypub.core.actor.get',
                ['username' => $username],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function generateInbox(LocalActorInterface|string $usernameOrLocalActor): Uri
    {
        $username = $this->getUsername($usernameOrLocalActor);
        return Uri::fromString(
            $this->urlGenerator->generate(
                'ddr.activitypub.core.inbox.get',
                ['username' => $username],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function generateOutbox(LocalActorInterface|string $usernameOrLocalActor): Uri
    {
        $username = $this->getUsername($usernameOrLocalActor);
        return Uri::fromString(
            $this->urlGenerator->generate(
                'ddr.activitypub.core.outbox.get',
                ['username' => $username],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function generateFollowers(LocalActorInterface|string $usernameOrLocalActor, ?int $page = null): Uri
    {
        $username = $this->getUsername($usernameOrLocalActor);
        $parameters = ['username' => $username];
        if (null !== $page) {
            $parameters['page'] = $page;
        }
        return Uri::fromString(
            $this->urlGenerator->generate(
                'ddr.activitypub.core.followers.get',
                $parameters,
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function generateFollowing(LocalActorInterface|string $usernameOrLocalActor, ?int $page = null): Uri
    {
        $username = $this->getUsername($usernameOrLocalActor);
        $parameters = ['username' => $username];
        if (null !== $page) {
            $parameters['page'] = $page;
        }
        return Uri::fromString(
            $this->urlGenerator->generate(
                'ddr.activitypub.core.following.get',
                $parameters,
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function matchUsername(Uri $uri): ?string
    {
        /* Match 'accept' header 'application/activity+json' */
        $this->urlMatcher->getContext()->setMethod('GET');
        $this->urlMatcher->getContext()->setParameter('_accept', 'application/activity+json');

        try {
            $parameters = $this->urlMatcher->match(Asserted::notNull($uri->getPathWithQueryAndFragment()));
        } catch (ResourceNotFoundException $e) {
            return null;
        }

        if ('ddr.activitypub.core.actor.get' !== ($parameters['_route'] ?? null)) {
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
