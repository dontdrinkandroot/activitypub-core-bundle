<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance\Spec;

use Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance\SerializationTestTrait;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\WebTestCase;

class VocabularyTest extends WebTestCase
{
    use SerializationTestTrait;

    public function testExample144(): void
    {
        $this->markTestSkipped('Non Normative and not working yet as we do not support non canonical types');
        $json = <<<JSON
{
 "@context": "https://www.w3.org/ns/activitystreams",
 "summary": "Activities in Project XYZ",
 "type": "Collection",
 "items": [
   {
     "summary": "Sally created a note",
     "type": "Create",
     "id": "http://activities.example.com/1",
     "actor": "http://sally.example.org",
     "object": {
      "summary": "A note",
       "type": "Note",
       "id": "http://notes.example.com/1",
       "content": "A note"
     },
     "context": {
       "type": "http://example.org/Project",
       "name": "Project XYZ"
     },
     "audience": {
       "type": "Group",
       "name": "Project XYZ Working Group"
     },
     "to": "http://john.example.org"
   },
   {
     "summary": "John liked Sally's note",
     "type": "Like",
     "id": "http://activities.example.com/1",
     "actor": "http://john.example.org",
     "object": "http://notes.example.com/1",
     "context": {
       "type": "http://example.org/Project",
       "name": "Project XYZ"
     },
     "audience": {
       "type": "Group",
       "name": "Project XYZ Working Group"
     },
     "to": "http://sally.example.org"
   }
 ]
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Activities in Project XYZ',
                'type' => 'Collection',
                'items' => [
                    [
                        'summary' => 'Sally created a note',
                        'type' => 'Create',
                        'id' => 'http://activities.example.com/1',
                        'actor' => ['http://sally.example.org'],
                        'object' => [
                            'summary' => 'A note',
                            'type' => 'Note',
                            'id' => 'http://notes.example.com/1',
                            'content' => 'A note',
                        ],
                        'context' => [
                            [
                                'type' => 'http://example.org/Project',
                                'name' => 'Project XYZ',
                            ],
                        ],
                        'audience' => [
                            [
                                'type' => 'Group',
                                'name' => 'Project XYZ Working Group',
                            ],
                        ],
                        'to' => 'http://john.example.org',
                    ],
                    [
                        'summary' => 'John liked Sally\'s note',
                        'type' => 'Like',
                        'id' => 'http://activities.example.com/1',
                        'actor' => ['http://john.example.org'],
                        'object' => 'http://notes.example.com/1',
                        'context' => [
                            [
                                'type' => 'http://example.org/Project',
                                'name' => 'Project XYZ',
                            ],
                        ],
                        'audience' => [
                            [
                                'type' => 'Group',
                                'name' => 'Project XYZ Working Group',
                            ],
                        ],
                        'to' => 'http://sally.example.org',
                    ],
                ],
            ],
            $restoredJson
        );
    }

    public function testExample145(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally's friends list",
  "type": "Collection",
  "items": [
    {
      "summary": "Sally is influenced by Joe",
      "type": "Relationship",
      "subject": {
        "type": "Person",
        "name": "Sally"
      },
      "relationship": "http://purl.org/vocab/relationship/influencedBy",
      "object": {
        "type": "Person",
        "name": "Joe"
      }
    },
    {
      "summary": "Sally is a friend of Jane",
      "type": "Relationship",
      "subject": {
        "type": "Person",
        "name": "Sally"
      },
      "relationship": "http://purl.org/vocab/relationship/friendOf",
      "object": {
        "type": "Person",
        "name": "Jane"
      }
    }
  ]
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally\'s friends list',
                'type' => 'Collection',
                'items' => [
                    [
                        'summary' => 'Sally is influenced by Joe',
                        'type' => 'Relationship',
                        'subject' => [
                            'type' => 'Person',
                            'name' => 'Sally',
                        ],
                        'relationship' => 'http://purl.org/vocab/relationship/influencedBy',
                        'object' => [
                            'type' => 'Person',
                            'name' => 'Joe',
                        ],
                    ],
                    [
                        'summary' => 'Sally is a friend of Jane',
                        'type' => 'Relationship',
                        'subject' => [
                            'type' => 'Person',
                            'name' => 'Sally',
                        ],
                        'relationship' => 'http://purl.org/vocab/relationship/friendOf',
                        'object' => [
                            'type' => 'Person',
                            'name' => 'Jane',
                        ],
                    ],
                ],
            ],
            $restoredJson
        );
    }
}
