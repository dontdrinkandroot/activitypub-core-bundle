<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\PublicKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use RuntimeException;

class ObjectResolverPublicKeyResolver implements PublicKeyResolverInterface
{
    public function __construct(private readonly ObjectResolverInterface $objectResolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Uri $keyId): PublicKey
    {
        $actorId = $keyId->withFragment(null);

        $publicKeyPem = $this->objectResolver->resolveTyped($actorId, Actor::class)?->publicKey?->publicKeyPem
            ?? throw new RuntimeException('Could not resolve public key for keyId: ' . $keyId);

        return new PublicKey(
            id: $keyId,
            owner: $actorId,
            publicKeyPem: $publicKeyPem
        );
    }
}
