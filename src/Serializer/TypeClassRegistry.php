<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Serializer;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\ActorType;
use InvalidArgumentException;

/**
 * Maps the type string to the class.
 */
class TypeClassRegistry
{
    /**
     * @var array<string, class-string<CoreType>>
     */
    private array $types = [];

    public function hasType(string $type): bool
    {
        return isset($this->types[$type]);
    }

    /**
     * @param string $type
     * @return class-string<CoreType>
     */
    public function getClass(string $type): string
    {
        if (!$this->hasType($type)) {
            throw new InvalidArgumentException('Unknown type: ' . $type);
        }

        return $this->types[$type];
    }

    /**
     * @param string $type
     * @param class-string<CoreType> $class
     */
    public function registerClass(string $type, string $class): TypeClassRegistry
    {
        if ($this->hasType($type)) {
            throw new InvalidArgumentException('Type already registered: ' . $type);
        }

        $this->types[$type] = $class;

        return $this;
    }

    public function hasClass(string $class): bool
    {
        return in_array($class, $this->types, true);
    }

    public function actorFromType(ActorType $type): Actor
    {
        $class = $this->getClass($type->value);
        if (!is_a($class, Actor::class, true)) {
            throw new InvalidArgumentException('Type is not an Actor: ' . $type->value);
        }

        return new $class();
    }
}
