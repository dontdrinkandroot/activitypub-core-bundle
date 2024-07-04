<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type;

/**
 * The @context in JSON-LD can take multiple forms, these include:
 * A single string representing a URL of the context:
 * {
 * "@context": "http://schema.org/"
 * }
 * Here the single URL http://schema.org/ is providing the context.
 * An array, allowing for multiple contexts:
 * {
 * "@context": ["http://schema.org/", "http://example.com/my_context"]
 * }
 * This allows for combining context from multiple vocabularies.
 * An object, enabling defining terms local to the document:
 * {
 * "@context": {
 * "name": "http://schema.org/name",
 * "description": "http://schema.org/description"
 * }
 * }
 * In this case, the context object is providing local definitions.
 * A combination of the above:
 * {
 * "@context": [
 * "http://schema.org/",
 * {
 * "myTerm": "http://example.com/my_term"
 * }
 * ]
 * }
 * Here @context is combining a URL context and a local term definition.
 */
class JsonLdContext
{
    /**
     * @param array<string|object> $values
     */
    public function __construct(public array $values)
    {
    }

    public function add(string|object $value): void
    {
        $this->values[] = $value;
    }
}
