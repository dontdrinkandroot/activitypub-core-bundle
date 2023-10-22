<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance\Spec\Vocabulary;

use Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance\SerializationTestTrait;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\WebTestCase;

class ActivityTypeTest extends WebTestCase
{
    use SerializationTestTrait;

    public function testExample9(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally accepted an invitation to a party",
  "type": "Accept",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": {
    "type": "Invite",
    "actor": "http://john.example.org",
    "object": {
      "type": "Event",
      "name": "Going-Away Party for Jim"
    }
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'summary' => 'Sally accepted an invitation to a party',
            'type' => 'Accept',
            'actor' => [
                'type' => 'Person',
                'name' => 'Sally',
            ],
            'object' => [
                'type' => 'Invite',
                'actor' => 'http://john.example.org',
                'object' => [
                    'type' => 'Event',
                    'name' => 'Going-Away Party for Jim',
                ],
            ],
        ], $restoredJson);
    }

    public function testExample10(): void
    {
        $json = <<<JSON
{
    "@context": "https://www.w3.org/ns/activitystreams",
    "summary": "Sally accepted Joe into the club",
    "type": "Accept",
    "actor": {
      "type": "Person",
      "name": "Sally"
    },
    "object": {
      "type": "Person",
      "name": "Joe"
    },
    "target": {
      "type": "Group",
      "name": "The Club"
    }
  }
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'summary' => 'Sally accepted Joe into the club',
            'type' => 'Accept',
            'actor' => [
                'type' => 'Person',
                'name' => 'Sally',
            ],
            'object' => [
                'type' => 'Person',
                'name' => 'Joe',
            ],
            'target' => [
                'type' => 'Group',
                'name' => 'The Club',
            ],
        ], $restoredJson);
    }

    public function testExample11(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally tentatively accepted an invitation to a party",
  "type": "TentativeAccept",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": {
    "type": "Invite",
    "actor": "http://john.example.org",
    "object": {
      "type": "Event",
      "name": "Going-Away Party for Jim"
    }
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'summary' => 'Sally tentatively accepted an invitation to a party',
            'type' => 'TentativeAccept',
            'actor' => [
                'type' => 'Person',
                'name' => 'Sally',
            ],
            'object' => [
                'type' => 'Invite',
                'actor' => 'http://john.example.org',
                'object' => [
                    'type' => 'Event',
                    'name' => 'Going-Away Party for Jim',
                ],
            ],
        ], $restoredJson);
    }

    public function testExample12(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally added an object",
  "type": "Add",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": "http://example.org/abc"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'summary' => 'Sally added an object',
            'type' => 'Add',
            'actor' => [
                'type' => 'Person',
                'name' => 'Sally',
            ],
            'object' => 'http://example.org/abc',
        ], $restoredJson);
    }

    public function testExample13(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally added a picture of her cat to her cat picture collection",
  "type": "Add",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": {
    "type": "Image",
    "name": "A picture of my cat",
    "url": "http://example.org/img/cat.png"
  },
  "origin": {
    "type": "Collection",
    "name": "Camera Roll"
  },
  "target": {
    "type": "Collection",
    "name": "My Cat Pictures"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally added a picture of her cat to her cat picture collection',
                'type' => 'Add',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'object' => [
                    'type' => 'Image',
                    'name' => 'A picture of my cat',
                    'url' => 'http://example.org/img/cat.png',
                ],
                'origin' => [
                    'type' => 'Collection',
                    'name' => 'Camera Roll',
                ],
                'target' => [
                    'type' => 'Collection',
                    'name' => 'My Cat Pictures',
                ],
            ],
            $restoredJson
        );
    }

    public function testExample14(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally arrived at work",
  "type": "Arrive",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "location": {
    "type": "Place",
    "name": "Work"
  },
  "origin": {
    "type": "Place",
    "name": "Home"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally arrived at work',
                'type' => 'Arrive',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'location' => [
                    'type' => 'Place',
                    'name' => 'Work',
                ],
                'origin' => [
                    'type' => 'Place',
                    'name' => 'Home',
                ],
            ],
            $restoredJson
        );
    }

    public function testExample15(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally created a note",
  "type": "Create",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": {
    "type": "Note",
    "name": "A Simple Note",
    "content": "This is a simple note"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally created a note',
                'type' => 'Create',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'object' => [
                    'type' => 'Note',
                    'name' => 'A Simple Note',
                    'content' => 'This is a simple note',
                ],
            ],
            $restoredJson
        );
    }

    public function testExample16(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally deleted a note",
  "type": "Delete",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": "http://example.org/notes/1",
  "origin": {
    "type": "Collection",
    "name": "Sally's Notes"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally deleted a note',
                'type' => 'Delete',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'object' => 'http://example.org/notes/1',
                'origin' => [
                    'type' => 'Collection',
                    'name' => 'Sally\'s Notes',
                ],
            ],
            $restoredJson
        );
    }

    public function testExample17(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally followed John",
  "type": "Follow",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": {
    "type": "Person",
    "name": "John"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally followed John',
                'type' => 'Follow',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'object' => [
                    'type' => 'Person',
                    'name' => 'John',
                ],
            ],
            $restoredJson
        );
    }

    public function testExample18(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally ignored a note",
  "type": "Ignore",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": "http://example.org/notes/1"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally ignored a note',
                'type' => 'Ignore',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'object' => 'http://example.org/notes/1',
            ],
            $restoredJson
        );
    }

    public function testExample19(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally joined a group",
  "type": "Join",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": {
    "type": "Group",
    "name": "A Simple Group"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally joined a group',
                'type' => 'Join',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'object' => [
                    'type' => 'Group',
                    'name' => 'A Simple Group',
                ],
            ],
            $restoredJson
        );
    }

    public function testExample20(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally left work",
  "type": "Leave",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": {
    "type": "Place",
    "name": "Work"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally left work',
                'type' => 'Leave',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'object' => [
                    'type' => 'Place',
                    'name' => 'Work',
                ],
            ],
            $restoredJson
        );
    }

    public function testExample21(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally left a group",
  "type": "Leave",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": {
    "type": "Group",
    "name": "A Simple Group"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally left a group',
                'type' => 'Leave',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'object' => [
                    'type' => 'Group',
                    'name' => 'A Simple Group',
                ],
            ],
            $restoredJson
        );
    }

    public function testExample22(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally liked a note",
  "type": "Like",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": "http://example.org/notes/1"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally liked a note',
                'type' => 'Like',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'object' => 'http://example.org/notes/1',
            ],
            $restoredJson
        );
    }

    public function testExample23(): void
    {
        $this->markTestSkipped('We are not supporting custom types yet.');
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally offered 50% off to Lewis",
  "type": "Offer",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": {
    "type": "http://www.types.example/ProductOffer",
    "name": "50% Off!"
  },
  "target": {
    "type": "Person",
    "name": "Lewis"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => [
                    'https://www.w3.org/ns/activitystreams',
                    'http://www.types.example/ProductOffer',
                ],
                'summary' => 'Sally offered 50% off to Lewis',
                'type' => 'Offer',
                'actor' => [
                    [
                        'type' => 'Person',
                        'name' => 'Sally',
                    ],
                ],
                'object' => [
                    [
                        'type' => 'http://www.types.example/ProductOffer',
                        'name' => '50% Off!',
                    ],
                ],
                'target' => [
                    [
                        'type' => 'Person',
                        'name' => 'Lewis',
                    ],
                ],
            ],
            $restoredJson
        );
    }

    public function testExample24(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally invited John and Lisa to a party",
  "type": "Invite",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": {
    "type": "Event",
    "name": "A Party"
  },
  "target": [
    {
      "type": "Person",
      "name": "John"
    },
    {
      "type": "Person",
      "name": "Lisa"
    }
  ]
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally invited John and Lisa to a party',
                'type' => 'Invite',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'object' => [
                    'type' => 'Event',
                    'name' => 'A Party',
                ],
                'target' => [
                    [
                        'type' => 'Person',
                        'name' => 'John',
                    ],
                    [
                        'type' => 'Person',
                        'name' => 'Lisa',
                    ],
                ],
            ],
            $restoredJson
        );
    }

    public function testExample25(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally rejected an invitation to a party",
  "type": "Reject",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": {
    "type": "Invite",
    "actor": "http://john.example.org",
    "object": {
      "type": "Event",
      "name": "Going-Away Party for Jim"
    }
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally rejected an invitation to a party',
                'type' => 'Reject',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'object' => [
                    'type' => 'Invite',
                    'actor' => 'http://john.example.org',
                    'object' => [
                        'type' => 'Event',
                        'name' => 'Going-Away Party for Jim',
                    ],
                ],
            ],
            $restoredJson
        );
    }

    public function testExample26(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally tentatively rejected an invitation to a party",
  "type": "TentativeReject",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": {
    "type": "Invite",
    "actor": "http://john.example.org",
    "object": {
      "type": "Event",
      "name": "Going-Away Party for Jim"
    }
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally tentatively rejected an invitation to a party',
                'type' => 'TentativeReject',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'object' => [
                    'type' => 'Invite',
                    'actor' => 'http://john.example.org',
                    'object' => [
                        'type' => 'Event',
                        'name' => 'Going-Away Party for Jim',
                    ],
                ],
            ],
            $restoredJson
        );
    }

    public function testExample27(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally removed a note from her notes folder",
  "type": "Remove",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": "http://example.org/notes/1",
  "target": {
    "type": "Collection",
    "name": "Notes Folder"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally removed a note from her notes folder',
                'type' => 'Remove',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'object' => 'http://example.org/notes/1',
                'target' => [
                    'type' => 'Collection',
                    'name' => 'Notes Folder',
                ],
            ],
            $restoredJson
        );
    }

    public function testExample28(): void
    {
        $this->markTestSkipped('We are not supporting custom types yet.');
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "The moderator removed Sally from a group",
  "type": "Remove",
  "actor": {
    "type": "http://example.org/Role",
    "name": "The Moderator"
  },
  "object": {
    "type": "Person",
    "name": "Sally"
  },
  "origin": {
    "type": "Group",
    "name": "A Simple Group"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => [
                    'https://www.w3.org/ns/activitystreams',
                    'http://example.org/Role',
                ],
                'summary' => 'The moderator removed Sally from a group',
                'type' => 'Remove',
                'actor' => [
                    [
                        'type' => 'http://example.org/Role',
                        'name' => 'The Moderator',
                    ],
                ],
                'object' => [
                    [
                        'type' => 'Person',
                        'name' => 'Sally',
                    ],
                ],
                'origin' => [
                    [
                        'type' => 'Group',
                        'name' => 'A Simple Group',
                    ],
                ],
            ],
            $restoredJson
        );
    }

    public function testExample29(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally retracted her offer to John",
  "type": "Undo",
  "actor": "http://sally.example.org",
  "object": {
    "type": "Offer",
    "actor": "http://sally.example.org",
    "object": "http://example.org/posts/1",
    "target": "http://john.example.org"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally retracted her offer to John',
                'type' => 'Undo',
                'actor' => 'http://sally.example.org',
                'object' => [
                    'type' => 'Offer',
                    'actor' => 'http://sally.example.org',
                    'object' => 'http://example.org/posts/1',
                    'target' => 'http://john.example.org',
                ],
            ],
            $restoredJson
        );
    }

    public function testExample30(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally updated her note",
  "type": "Update",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": "http://example.org/notes/1"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally updated her note',
                'type' => 'Update',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'object' => 'http://example.org/notes/1',
            ],
            $restoredJson
        );
    }

    public function testExample31(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally read an article",
  "type": "View",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": {
    "type": "Article",
    "name": "What You Should Know About Activity Streams"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally read an article',
                'type' => 'View',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'object' => [
                    'type' => 'Article',
                    'name' => 'What You Should Know About Activity Streams',
                ],
            ],
            $restoredJson
        );
    }

    public function testExample32(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally listened to a piece of music",
  "type": "Listen",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": "http://example.org/music.mp3"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally listened to a piece of music',
                'type' => 'Listen',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'object' => 'http://example.org/music.mp3',
            ],
            $restoredJson
        );
    }

    public function testExample33(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally read a blog post",
  "type": "Read",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": "http://example.org/posts/1"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally read a blog post',
                'type' => 'Read',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'object' => 'http://example.org/posts/1',
            ],
            $restoredJson
        );
    }

    public function testExample34(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally moved a post from List A to List B",
  "type": "Move",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "object": "http://example.org/posts/1",
  "target": {
    "type": "Collection",
    "name": "List B"
  },
  "origin": {
    "type": "Collection",
    "name": "List A"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally moved a post from List A to List B',
                'type' => 'Move',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'object' => 'http://example.org/posts/1',
                'target' => [
                    'type' => 'Collection',
                    'name' => 'List B',
                ],
                'origin' => [
                    'type' => 'Collection',
                    'name' => 'List A',
                ],
            ],
            $restoredJson
        );
    }

    public function testExample35(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally went home from work",
  "type": "Travel",
  "actor": {
    "type": "Person",
    "name": "Sally"
  },
  "target": {
    "type": "Place",
    "name": "Home"
  },
  "origin": {
    "type": "Place",
    "name": "Work"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally went home from work',
                'type' => 'Travel',
                'actor' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'target' => [
                    'type' => 'Place',
                    'name' => 'Home',
                ],
                'origin' => [
                    'type' => 'Place',
                    'name' => 'Work',
                ],
            ],
            $restoredJson
        );
    }

    public function testExample36(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally announced that she had arrived at work",
  "type": "Announce",
  "actor": {
    "type": "Person",
    "id": "http://sally.example.org",
    "name": "Sally"
  },
  "object": {
    "type": "Arrive",
    "actor": "http://sally.example.org",
    "location": {
      "type": "Place",
      "name": "Work"
    }
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally announced that she had arrived at work',
                'type' => 'Announce',
                'actor' => [
                    'type' => 'Person',
                    'id' => 'http://sally.example.org',
                    'name' => 'Sally',
                ],
                'object' => [
                    'type' => 'Arrive',
                    'actor' => 'http://sally.example.org',
                    'location' => [
                        'type' => 'Place',
                        'name' => 'Work',
                    ],
                ],
            ],
            $restoredJson
        );
    }

    public function testExample37(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally blocked Joe",
  "type": "Block",
  "actor": "http://sally.example.org",
  "object": "http://joe.example.org"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally blocked Joe',
                'type' => 'Block',
                'actor' => 'http://sally.example.org',
                'object' => 'http://joe.example.org',
            ],
            $restoredJson
        );
    }

    public function testExample38(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally flagged an inappropriate note",
  "type": "Flag",
  "actor": "http://sally.example.org",
  "object": {
    "type": "Note",
    "content": "An inappropriate note"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally flagged an inappropriate note',
                'type' => 'Flag',
                'actor' => 'http://sally.example.org',
                'object' => [
                    'type' => 'Note',
                    'content' => 'An inappropriate note',
                ],
            ],
            $restoredJson
        );
    }

    public function testExample39(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally disliked a post",
  "type": "Dislike",
  "actor": "http://sally.example.org",
  "object": "http://example.org/posts/1"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Sally disliked a post',
                'type' => 'Dislike',
                'actor' => 'http://sally.example.org',
                'object' => 'http://example.org/posts/1',
            ],
            $restoredJson
        );
    }

    public function testExample40(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Question",
  "name": "What is the answer?",
  "oneOf": [
    {
      "type": "Note",
      "name": "Option A"
    },
    {
      "type": "Note",
      "name": "Option B"
    }
  ]
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'type' => 'Question',
                'name' => 'What is the answer?',
                'oneOf' => [
                    [
                        'type' => 'Note',
                        'name' => 'Option A',
                    ],
                    [
                        'type' => 'Note',
                        'name' => 'Option B',
                    ],
                ],
            ],
            $restoredJson
        );
    }

    public function testExample41(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Question",
  "name": "What is the answer?",
  "closed": "2016-05-10T00:00:00Z"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'type' => 'Question',
                'name' => 'What is the answer?',
                'closed' => '2016-05-10T00:00:00Z',
            ],
            $restoredJson
        );
    }
}
