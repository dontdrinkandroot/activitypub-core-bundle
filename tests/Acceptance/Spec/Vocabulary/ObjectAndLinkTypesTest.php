<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance\Spec\Vocabulary;

use Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance\SerializationTestTrait;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\WebTestCase;

class ObjectAndLinkTypesTest extends WebTestCase
{
    use SerializationTestTrait;

    public function testRelationship(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Sally is an acquaintance of John",
  "type": "Relationship",
  "subject": {
    "type": "Person",
    "name": "Sally"
  },
  "relationship": "http://purl.org/vocab/relationship/acquaintanceOf",
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
                'summary' => 'Sally is an acquaintance of John',
                'type' => 'Relationship',
                'subject' => [
                    'type' => 'Person',
                    'name' => 'Sally',
                ],
                'relationship' => 'http://purl.org/vocab/relationship/acquaintanceOf',
                'object' => [
                    'type' => 'Person',
                    'name' => 'John',
                ],
            ],
            $restoredJson
        );
    }

    public function testArticle(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Article",
  "name": "What a Crazy Day I Had",
  "content": "<div>... you will never believe ...</div>",
  "attributedTo": "http://sally.example.org"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'type' => 'Article',
                'name' => 'What a Crazy Day I Had',
                'content' => '<div>... you will never believe ...</div>',
                'attributedTo' => 'http://sally.example.org',
            ],
            $restoredJson
        );
    }

    public function testDocument(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Document",
  "name": "4Q Sales Forecast",
  "url": "http://example.org/4q-sales-forecast.pdf"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'type' => 'Document',
                'name' => '4Q Sales Forecast',
                'url' => 'http://example.org/4q-sales-forecast.pdf',
            ],
            $restoredJson
        );
    }

    public function testAudio(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Audio",
  "name": "Interview With A Famous Technologist",
  "url": {
    "type": "Link",
    "href": "http://example.org/podcast.mp3",
    "mediaType": "audio/mp3"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'type' => 'Audio',
                'name' => 'Interview With A Famous Technologist',
                'url' => [
                    'type' => 'Link',
                    'href' => 'http://example.org/podcast.mp3',
                    'mediaType' => 'audio/mp3',
                ],
            ],
            $restoredJson
        );
    }

    public function testImage(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Image",
  "name": "Cat Jumping on Wagon",
  "url": [
    {
      "type": "Link",
      "href": "http://example.org/image.jpeg",
      "mediaType": "image/jpeg"
    },
    {
      "type": "Link",
      "href": "http://example.org/image.png",
      "mediaType": "image/png"
    }
  ]
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'type' => 'Image',
                'name' => 'Cat Jumping on Wagon',
                'url' => [
                    [
                        'type' => 'Link',
                        'href' => 'http://example.org/image.jpeg',
                        'mediaType' => 'image/jpeg',
                    ],
                    [
                        'type' => 'Link',
                        'href' => 'http://example.org/image.png',
                        'mediaType' => 'image/png',
                    ],
                ],
            ],
            $restoredJson
        );
    }

    public function testVideo(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Video",
  "name": "Puppy Plays With Ball",
  "url": "http://example.org/video.mkv",
  "duration": "PT2H"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'type' => 'Video',
                'name' => 'Puppy Plays With Ball',
                'url' => 'http://example.org/video.mkv',
                'duration' => 'PT2H',
            ],
            $restoredJson
        );
    }

    public function testNote(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Note",
  "name": "A Word of Warning",
  "content": "Looks like it is going to rain today. Bring an umbrella!"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'type' => 'Note',
                'name' => 'A Word of Warning',
                'content' => 'Looks like it is going to rain today. Bring an umbrella!',
            ],
            $restoredJson
        );
    }

    public function testPage(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Page",
  "name": "Omaha Weather Report",
  "url": "http://example.org/weather-in-omaha.html"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'type' => 'Page',
                'name' => 'Omaha Weather Report',
                'url' => 'http://example.org/weather-in-omaha.html',
            ],
            $restoredJson
        );
    }

    public function testEvent(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Event",
  "name": "Going-Away Party for Jim",
  "startTime": "2014-12-31T23:00:00-08:00",
  "endTime": "2015-01-01T06:00:00-08:00"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'type' => 'Event',
                'name' => 'Going-Away Party for Jim',
                'startTime' => '2014-12-31T23:00:00-08:00',
                'endTime' => '2015-01-01T06:00:00-08:00',
            ],
            $restoredJson
        );
    }

    public function testPlaceLogical(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Place",
  "name": "Work"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'type' => 'Place',
                'name' => 'Work',
            ],
            $restoredJson
        );
    }

    public function testPlacePhysical(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Place",
  "name": "Fresno Area",
  "latitude": 36.75,
  "longitude": 119.7667,
  "radius": 15,
  "units": "miles"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'type' => 'Place',
                'name' => 'Fresno Area',
                'latitude' => 36.75,
                'longitude' => 119.7667,
                'radius' => 15,
                'units' => 'miles',
            ],
            $restoredJson
        );
    }

    public function testMention(): void
    {
        $this->markTestSkipped('Strangely summary is not defined in the spec');
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "summary": "Mention of Joe by Carrie in her note",
  "type": "Mention",
  "href": "http://example.org/joe",
  "name": "Joe"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'summary' => 'Mention of Joe by Carrie in her note',
                'type' => 'Mention',
                'href' => 'http://example.org/joe',
                'name' => 'Joe',
            ],
            $restoredJson
        );
    }

    public function testProfile(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Profile",
  "summary": "Sally's Profile",
  "describes": {
    "type": "Person",
    "name": "Sally Smith"
  }
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'type' => 'Profile',
                'summary' => 'Sally\'s Profile',
                'describes' => [
                    'type' => 'Person',
                    'name' => 'Sally Smith',
                ],
            ],
            $restoredJson
        );
    }

    public function testTombstone(): void
    {
        // The spec json is missing the @context

        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "OrderedCollection",
  "totalItems": 3,
  "name": "Vacation photos 2016",
  "orderedItems": [
    {
      "type": "Image",
      "id": "http://image.example/1"
    },
    {
      "type": "Tombstone",
      "formerType": "Image",
      "id": "http://image.example/2",
      "deleted": "2016-03-17T00:00:00Z"
    },
    {
      "type": "Image",
      "id": "http://image.example/3"
    }
  ]
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals(
            [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'type' => 'OrderedCollection',
                'totalItems' => 3,
                'name' => 'Vacation photos 2016',
                'orderedItems' => [
                    [
                        'type' => 'Image',
                        'id' => 'http://image.example/1',
                    ],
                    [
                        'type' => 'Tombstone',
                        'formerType' => 'Image',
                        'id' => 'http://image.example/2',
                        'deleted' => '2016-03-17T00:00:00Z',
                    ],
                    [
                        'type' => 'Image',
                        'id' => 'http://image.example/3',
                    ],
                ],
            ],
            $restoredJson
        );
    }
}
