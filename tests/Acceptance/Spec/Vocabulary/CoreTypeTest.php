<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance\Spec\Vocabulary;

use Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance\SerializationTestTrait;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\WebTestCase;

class CoreTypeTest extends WebTestCase
{
    use SerializationTestTrait;

    public function testExample1(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Object",
  "id": "http://www.test.example/object/1",
  "name": "A Simple, non-specific object"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'type' => 'Object',
            'id' => 'http://www.test.example/object/1',
            'name' => 'A Simple, non-specific object',
        ], $restoredJson);
    }

    public function testExample2(): void
    {
        $json = <<<JSON
            {
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Link",
  "href": "http://example.org/abc",
  "hreflang": "en",
  "mediaType": "text/html",
  "name": "An example link"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'type' => 'Link',
            'href' => 'http://example.org/abc',
            'hreflang' => 'en',
            'mediaType' => 'text/html',
            'name' => 'An example link',
        ], $restoredJson);
    }

    public function testExample3(): void
    {
        $json = <<<JSON
            {
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Activity",
  "summary": "Sally did something to a note",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": {
    "type": "Note",
    "name": "A Note"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'type' => 'Activity',
            'summary' => 'Sally did something to a note',
            'actor' => [
                'type' => 'Person',
                'name' => 'Sally',
            ],
            'object' => [
                'type' => 'Note',
                'name' => 'A Note',
            ],
        ], $restoredJson);
    }

    public function testExample4(): void
    {
        $json = <<<JSON
            {
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Travel",
  "summary": "Sally went to work",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "target": {
    "type": "Place",
    "name": "Work"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'type' => 'Travel',
            'summary' => 'Sally went to work',
            'actor' => [
                'type' => 'Person',
                'name' => 'Sally',
            ],
            'target' => [
                'type' => 'Place',
                'name' => 'Work',
            ],
        ], $restoredJson);
    }

    public function testExample5(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally's notes",
  "type": "Collection",
  "totalItems": 2,
  "items": [
    {
      "type": "Note",
      "name": "A Simple Note"
    },
    {
      "type": "Note",
      "name": "Another Simple Note"
    }
  ]
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'summary' => 'Sally\'s notes',
            'type' => 'Collection',
            'totalItems' => 2,
            'items' => [
                [
                    'type' => 'Note',
                    'name' => 'A Simple Note',
                ],
                [
                    'type' => 'Note',
                    'name' => 'Another Simple Note',
                ],
            ],
        ], $restoredJson);
    }

    public function testExample6(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally's notes",
  "type": "OrderedCollection",
  "totalItems": 2,
  "orderedItems": [
    {
      "type": "Note",
      "name": "A Simple Note"
    },
    {
      "type": "Note",
      "name": "Another Simple Note"
    }
  ]
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'summary' => 'Sally\'s notes',
            'type' => 'OrderedCollection',
            'totalItems' => 2,
            'orderedItems' => [
                [
                    'type' => 'Note',
                    'name' => 'A Simple Note',
                ],
                [
                    'type' => 'Note',
                    'name' => 'Another Simple Note',
                ],
            ],
        ], $restoredJson);
    }

    public function testExample7(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Page 1 of Sally's notes",
  "type": "CollectionPage",
  "id": "http://example.org/foo?page=1",
  "partOf": "http://example.org/foo",
  "items": [
    {
      "type": "Note",
      "name": "A Simple Note"
    },
    {
      "type": "Note",
      "name": "Another Simple Note"
    }
  ]
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'summary' => 'Page 1 of Sally\'s notes',
            'type' => 'CollectionPage',
            'id' => 'http://example.org/foo?page=1',
            'partOf' => 'http://example.org/foo',
            'items' => [
                [
                    'type' => 'Note',
                    'name' => 'A Simple Note',
                ],
                [
                    'type' => 'Note',
                    'name' => 'Another Simple Note',
                ],
            ],
        ], $restoredJson);
    }

    public function testExample8(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Page 1 of Sally's notes",
  "type": "OrderedCollectionPage",
  "id": "http://example.org/foo?page=1",
  "partOf": "http://example.org/foo",
  "orderedItems": [
    {
      "type": "Note",
      "name": "A Simple Note"
    },
    {
      "type": "Note",
      "name": "Another Simple Note"
    }
  ]
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'summary' => 'Page 1 of Sally\'s notes',
            'type' => 'OrderedCollectionPage',
            'id' => 'http://example.org/foo?page=1',
            'partOf' => 'http://example.org/foo',
            'orderedItems' => [
                [
                    'type' => 'Note',
                    'name' => 'A Simple Note',
                ],
                [
                    'type' => 'Note',
                    'name' => 'Another Simple Note',
                ],
            ],
        ], $restoredJson);
    }
}
