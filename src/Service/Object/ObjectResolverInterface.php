<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

interface ObjectResolverInterface
{
    public function resolve(LinkableObject|Uri $object, ?SignKey $signKey = null): ?CoreObject;

    /**
     * @template T of CoreObject
     * @param class-string<T> $type
     * @return T|null
     */
    public function resolveTyped(LinkableObject|Uri $object, string $type, ?SignKey $signKey = null): ?CoreObject;
}
