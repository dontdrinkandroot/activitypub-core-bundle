<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance\Spec\Vocabulary;

use Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance\SerializationTestTrait;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\WebTestCase;

class PropertiesTest extends WebTestCase
{
    use SerializationTestTrait;

    public function testExample81(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "name": "A simple note",
  "type": "Note",
  "content": "This is all there is.",
  "image": {
    "type": "Image",
    "name": "A Cat",
    "url": "http://example.org/cat.png"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'type' => 'Note',
            'name' => 'A simple note',
            'content' => 'This is all there is.',
            'image' => [
                'type' => 'Image',
                'name' => 'A Cat',
                'url' => 'http://example.org/cat.png'
            ]
        ], $restoredJson);
    }

    public function testExample82(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "name": "A simple note",
  "type": "Note",
  "content": "This is all there is.",
  "image": [
    {
      "type": "Image",
      "name": "Cat 1",
      "url": "http://example.org/cat1.png"
    },
    {
      "type": "Image",
      "name": "Cat 2",
      "url": "http://example.org/cat2.png"
    }
  ]
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'type' => 'Note',
            'name' => 'A simple note',
            'content' => 'This is all there is.',
            'image' => [
                [
                    'type' => 'Image',
                    'name' => 'Cat 1',
                    'url' => 'http://example.org/cat1.png'
                ],
                [
                    'type' => 'Image',
                    'name' => 'Cat 2',
                    'url' => 'http://example.org/cat2.png'
                ]
            ]
        ], $restoredJson);
    }
}
