<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance\Spec;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Activity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Link;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\ActivityType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Note;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\ActivityStreamEncoder;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\WebTestCase;
use Symfony\Component\Serializer\SerializerInterface;

class RecommendationTest extends WebTestCase
{
    public function testExample1(): void
    {
        $json = <<<JSON
{"@context": "https://www.w3.org/ns/activitystreams",
 "type": "Person",
 "id": "https://social.example/alyssa/",
 "name": "Alyssa P. Hacker",
 "preferredUsername": "alyssa",
 "summary": "Lisp enthusiast hailing from MIT",
 "inbox": "https://social.example/alyssa/inbox/",
 "outbox": "https://social.example/alyssa/outbox/",
 "followers": "https://social.example/alyssa/followers/",
 "following": "https://social.example/alyssa/following/",
 "liked": "https://social.example/alyssa/liked/"}
JSON;

        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            "@context" => "https://www.w3.org/ns/activitystreams",
            "followers" => "https://social.example/alyssa/followers/",
            "following" => "https://social.example/alyssa/following/",
            "id" => "https://social.example/alyssa/",
            "inbox" => "https://social.example/alyssa/inbox/",
            "liked" => "https://social.example/alyssa/liked/",
            "name" => "Alyssa P. Hacker",
            "outbox" => "https://social.example/alyssa/outbox/",
            "preferredUsername" => "alyssa",
            "summary" => "Lisp enthusiast hailing from MIT",
            "type" => "Person"
        ], $restoredJson);
    }

    public function testExample2(): void
    {
        $json = <<<JSON
{"@context": "https://www.w3.org/ns/activitystreams",
 "type": "Note",
 "to": ["https://chatty.example/ben/"],
 "attributedTo": "https://social.example/alyssa/",
 "content": "Say, did you finish reading that book I lent you?"}
JSON;

        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            "@context" => "https://www.w3.org/ns/activitystreams",
            "attributedTo" => "https://social.example/alyssa/",
            "content" => "Say, did you finish reading that book I lent you?",
            "to" => "https://chatty.example/ben/",
            "type" => "Note"
        ], $restoredJson);
    }

    public function testExample3(): void
    {
        $serializer = self::getService(SerializerInterface::class);

        $json = <<<JSON
{"@context": "https://www.w3.org/ns/activitystreams",
 "type": "Create",
 "id": "https://social.example/alyssa/posts/a29a6843-9feb-4c74-a7f7-081b9c9201d3",
 "to": ["https://chatty.example/ben/"],
 "actor": "https://social.example/alyssa/",
 "object": {"type": "Note",
            "id": "https://social.example/alyssa/posts/49e2d03d-b53a-4c4c-a95c-94a6abf45a19",
            "attributedTo": "https://social.example/alyssa/",
            "to": ["https://chatty.example/ben/"],
            "content": "Say, did you finish reading that book I lent you?"}}
JSON;

        $object = $serializer->deserialize($json, CoreType::class, ActivityStreamEncoder::FORMAT);
        $this->assertInstanceOf(Activity::class, $object);
        $this->assertEquals(ActivityType::CREATE->value, $object->getType());
        $this->assertInstanceOf(Uri::class, $object->id);
        $this->assertEquals(
            'https://social.example/alyssa/posts/a29a6843-9feb-4c74-a7f7-081b9c9201d3',
            (string)$object->id
        );
        $this->assertNotNull($object->to);
        $this->assertCount(1, $object->to);
        $this->assertInstanceOf(Link::class, $object->to[0]->link);
        $this->assertEquals('https://chatty.example/ben/', $object->to[0]->link->href);

        $this->assertNotNull($object->actor);
        $this->assertCount(1, $object->actor);
        $this->assertInstanceOf(Link::class, $object->actor[0]->link);
        $this->assertEquals('https://social.example/alyssa/', $object->actor[0]->link->href);

        $childObjectOrLink = $object->object;
        $this->assertInstanceOf(LinkableObject::class, $childObjectOrLink);
        $childObject = $childObjectOrLink->object;
        $this->assertInstanceOf(Note::class, $childObject);
        $this->assertInstanceOf(Uri::class, $childObject->id);
        $this->assertEquals(
            'https://social.example/alyssa/posts/49e2d03d-b53a-4c4c-a95c-94a6abf45a19',
            (string)$childObject->id
        );

        $this->assertNotNull($childObject->attributedTo);
        $this->assertCount(1, $childObject->attributedTo);
        $this->assertInstanceOf(Link::class, $childObject->attributedTo[0]->link);
        $this->assertEquals('https://social.example/alyssa/', $childObject->attributedTo[0]->link->href);

        $this->assertNotNull($childObject->to);
        $this->assertCount(1, $childObject->to);
        $this->assertInstanceOf(Link::class, $childObject->to[0]->link);
        $this->assertEquals('https://chatty.example/ben/', $childObject->to[0]->link->href);

        $this->assertEquals(
            'Say, did you finish reading that book I lent you?',
            $childObject->content
        );

        $restoredJson = json_decode(
            $serializer->serialize($object, ActivityStreamEncoder::FORMAT),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        $this->assertEquals([
            "@context" => "https://www.w3.org/ns/activitystreams",
            "actor" => "https://social.example/alyssa/",
            "id" => "https://social.example/alyssa/posts/a29a6843-9feb-4c74-a7f7-081b9c9201d3",
            "object" => [
                "attributedTo" => "https://social.example/alyssa/",
                "content" => "Say, did you finish reading that book I lent you?",
                "id" => "https://social.example/alyssa/posts/49e2d03d-b53a-4c4c-a95c-94a6abf45a19",
                "to" => "https://chatty.example/ben/",
                "type" => "Note"
            ],
            "to" => "https://chatty.example/ben/",
            "type" => "Create"
        ], $restoredJson);
    }

    public function testExample4(): void
    {
        $json = <<<JSON
{"@context": "https://www.w3.org/ns/activitystreams",
 "type": "Create",
 "id": "https://chatty.example/ben/p/51086",
 "to": ["https://social.example/alyssa/"],
 "actor": "https://chatty.example/ben/",
 "object": {"type": "Note",
            "id": "https://chatty.example/ben/p/51085",
            "attributedTo": "https://chatty.example/ben/",
            "to": ["https://social.example/alyssa/"],
            "inReplyTo": "https://social.example/alyssa/posts/49e2d03d-b53a-4c4c-a95c-94a6abf45a19",
            "content": "<p>Argh, yeah, sorry, I'll get it back to you tomorrow.</p><p>I was reviewing the section on register machines, since it's been a while since I wrote one.</p>"
            }
}
JSON;

        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            "@context" => "https://www.w3.org/ns/activitystreams",
            "actor" => "https://chatty.example/ben/",
            "id" => "https://chatty.example/ben/p/51086",
            "object" => [
                "attributedTo" => "https://chatty.example/ben/",
                "content" => "<p>Argh, yeah, sorry, I'll get it back to you tomorrow.</p><p>I was reviewing the section on register machines, since it's been a while since I wrote one.</p>",
                "id" => "https://chatty.example/ben/p/51085",
                "inReplyTo" => "https://social.example/alyssa/posts/49e2d03d-b53a-4c4c-a95c-94a6abf45a19",
                "to" => "https://social.example/alyssa/",
                "type" => "Note"
            ],
            "to" => "https://social.example/alyssa/",
            "type" => "Create"
        ], $restoredJson);
    }

    public function testExample5(): void
    {
        $json = <<<JSON
{"@context": "https://www.w3.org/ns/activitystreams",
 "type": "Like",
 "id": "https://social.example/alyssa/posts/5312e10e-5110-42e5-a09b-934882b3ecec",
 "to": ["https://chatty.example/ben/"],
 "actor": "https://social.example/alyssa/",
 "object": "https://chatty.example/ben/p/51086"}
JSON;

        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            "@context" => "https://www.w3.org/ns/activitystreams",
            "actor" => "https://social.example/alyssa/",
            "id" => "https://social.example/alyssa/posts/5312e10e-5110-42e5-a09b-934882b3ecec",
            "object" => "https://chatty.example/ben/p/51086",
            "to" => "https://chatty.example/ben/",
            "type" => "Like"
        ], $restoredJson);
    }

    public function testExample6(): void
    {
        $json = <<<JSON
{"@context": "https://www.w3.org/ns/activitystreams",
 "type": "Create",
 "id": "https://social.example/alyssa/posts/9282e9cc-14d0-42b3-a758-d6aeca6c876b",
 "to": ["https://social.example/alyssa/followers/",
        "https://www.w3.org/ns/activitystreams#Public"],
 "actor": "https://social.example/alyssa/",
 "object": {"type": "Note",
            "id": "https://social.example/alyssa/posts/d18c55d4-8a63-4181-9745-4e6cf7938fa1",
            "attributedTo": "https://social.example/alyssa/",
            "to": ["https://social.example/alyssa/followers/",
                   "https://www.w3.org/ns/activitystreams#Public"],
            "content": "Lending books to friends is nice.  Getting them back is even nicer! :)"}}
JSON;

        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            "@context" => "https://www.w3.org/ns/activitystreams",
            "actor" => "https://social.example/alyssa/",
            "id" => "https://social.example/alyssa/posts/9282e9cc-14d0-42b3-a758-d6aeca6c876b",
            "object" => [
                "attributedTo" => "https://social.example/alyssa/",
                "content" => "Lending books to friends is nice.  Getting them back is even nicer! :)",
                "id" => "https://social.example/alyssa/posts/d18c55d4-8a63-4181-9745-4e6cf7938fa1",
                "to" => [
                    "https://social.example/alyssa/followers/",
                    "https://www.w3.org/ns/activitystreams#Public"
                ],
                "type" => "Note"
            ],
            "to" => [
                "https://social.example/alyssa/followers/",
                "https://www.w3.org/ns/activitystreams#Public"
            ],
            "type" => "Create"
        ], $restoredJson);
    }

    public function testExample7(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Like",
  "actor": "https://example.net/~mallory",
  "to": ["https://hatchat.example/sarah/",
         "https://example.com/peeps/john/"],
  "object": {
    "@context": {"@language": "en"},
    "id": "https://example.org/~alice/note/23",
    "type": "Note",
    "attributedTo": "https://example.org/~alice",
    "content": "I'm a goat"
  }
}
JSON;

        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            "@context" => "https://www.w3.org/ns/activitystreams",
            "actor" => "https://example.net/~mallory",
            "object" => [
                "@context" => [
                    "@language" => "en"
                ],
                "attributedTo" => "https://example.org/~alice",
                "content" => "I'm a goat",
                "id" => "https://example.org/~alice/note/23",
                "type" => "Note"
            ],
            "to" => [
                "https://hatchat.example/sarah/",
                "https://example.com/peeps/john/"
            ],
            "type" => "Like"
        ], $restoredJson);
    }

    public function testExample8(): void
    {
        $json = <<<JSON
{
    "@context": ["https://www.w3.org/ns/activitystreams",{"@language": "en"}],
    "type": "Note",
    "id": "http://postparty.example/p/2415",
    "content": "<p>I <em>really</em> like strawberries!</p>",
    "source": {
        "content": "I *really* like strawberries!",
        "mediaType": "text/markdown"
    }
}
JSON;

        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            "@context" => [
                "https://www.w3.org/ns/activitystreams",
                [
                    "@language" => "en"
                ]
            ],
            "content" => "<p>I <em>really</em> like strawberries!</p>",
            "id" => "http://postparty.example/p/2415",
            "source" => [
                "content" => "I *really* like strawberries!",
                "mediaType" => "text/markdown"
            ],
            "type" => "Note"
        ], $restoredJson);
    }

    public function testExample9(): void
    {
        $json = <<<JSON
{
    "@context": ["https://www.w3.org/ns/activitystreams",{"@language": "ja"}],
    "type": "Person",
    "id": "https://kenzoishii.example.com/",
    "following": "https://kenzoishii.example.com/following.json",
    "followers": "https://kenzoishii.example.com/followers.json",
    "liked": "https://kenzoishii.example.com/liked.json",
    "inbox": "https://kenzoishii.example.com/inbox.json",
    "outbox": "https://kenzoishii.example.com/feed.json",
    "preferredUsername": "kenzoishii",
    "name": "石井健蔵",
    "summary": "この方はただの例です",
    "icon": [
        "https://kenzoishii.example.com/image/165987aklre4"
    ]
}
JSON;

        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            "@context" => [
                "https://www.w3.org/ns/activitystreams",
                [
                    "@language" => "ja"
                ]
            ],
            "following" => "https://kenzoishii.example.com/following.json",
            "followers" => "https://kenzoishii.example.com/followers.json",
            "liked" => "https://kenzoishii.example.com/liked.json",
            "inbox" => "https://kenzoishii.example.com/inbox.json",
            "outbox" => "https://kenzoishii.example.com/feed.json",
            "preferredUsername" => "kenzoishii",
            "name" => "石井健蔵",
            "summary" => "この方はただの例です",
            "icon" => "https://kenzoishii.example.com/image/165987aklre4",
            "id" => "https://kenzoishii.example.com/",
            "type" => "Person"
        ], $restoredJson);
    }

    public function testExample10(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "id": "https://www.w3.org/ns/activitystreams#Public",
  "type": "Collection"
}
JSON;

        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            "@context" => "https://www.w3.org/ns/activitystreams",
            "id" => "https://www.w3.org/ns/activitystreams#Public",
            "type" => "Collection"
        ], $restoredJson);
    }

    public function testExample11(): void
    {
        $json = <<<JSON
{
  "@context": ["https://www.w3.org/ns/activitystreams",
               {"@language": "en"}],
  "type": "Like",
  "actor": "https://dustycloud.org/chris/",
  "name": "Chris liked 'Minimal ActivityPub update client'",
  "object": "https://rhiaro.co.uk/2016/05/minimal-activitypub",
  "to": ["https://rhiaro.co.uk/#amy",
         "https://dustycloud.org/followers",
         "https://rhiaro.co.uk/followers/"],
  "cc": "https://e14n.com/evan"
}
JSON;

        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            "@context" => [
                "https://www.w3.org/ns/activitystreams",
                [
                    "@language" => "en"
                ]
            ],
            "actor" => "https://dustycloud.org/chris/",
            "cc" => "https://e14n.com/evan",
            "name" => "Chris liked 'Minimal ActivityPub update client'",
            "object" => "https://rhiaro.co.uk/2016/05/minimal-activitypub",
            "to" => [
                "https://rhiaro.co.uk/#amy",
                "https://dustycloud.org/followers",
                "https://rhiaro.co.uk/followers/"
            ],
            "type" => "Like"
        ], $restoredJson);
    }

    public function testExample13(): void
    {
        $json = <<<JSON
{
  "@context": ["https://www.w3.org/ns/activitystreams",
               {"@language": "en-GB"}],
  "id": "https://rhiaro.co.uk/2016/05/minimal-activitypub",
  "type": "Article",
  "name": "Minimal ActivityPub update client",
  "content": "Today I finished morph, a client for posting ActivityStreams2...",
  "attributedTo": "https://rhiaro.co.uk/#amy",
  "to": "https://rhiaro.co.uk/followers/",
  "cc": "https://e14n.com/evan"
}
JSON;

        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            "@context" => [
                "https://www.w3.org/ns/activitystreams",
                [
                    "@language" => "en-GB"
                ]
            ],
            "attributedTo" => "https://rhiaro.co.uk/#amy",
            "cc" => "https://e14n.com/evan",
            "content" => "Today I finished morph, a client for posting ActivityStreams2...",
            "id" => "https://rhiaro.co.uk/2016/05/minimal-activitypub",
            "name" => "Minimal ActivityPub update client",
            "to" => "https://rhiaro.co.uk/followers/",
            "type" => "Article"
        ], $restoredJson);
    }

    public function testExample14(): void
    {
        $json = <<<JSON
{
  "@context": ["https://www.w3.org/ns/activitystreams",
               {"@language": "en"}],
  "type": "Like",
  "actor": "https://dustycloud.org/chris/",
  "summary": "Chris liked 'Minimal ActivityPub update client'",
  "object": "https://rhiaro.co.uk/2016/05/minimal-activitypub",
  "to": ["https://rhiaro.co.uk/#amy",
         "https://dustycloud.org/followers",
         "https://rhiaro.co.uk/followers/"],
  "cc": "https://e14n.com/evan"
}
JSON;

        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            "@context" => [
                "https://www.w3.org/ns/activitystreams",
                [
                    "@language" => "en"
                ]
            ],
            "actor" => "https://dustycloud.org/chris/",
            "cc" => "https://e14n.com/evan",
            "object" => "https://rhiaro.co.uk/2016/05/minimal-activitypub",
            "summary" => "Chris liked 'Minimal ActivityPub update client'",
            "to" => [
                "https://rhiaro.co.uk/#amy",
                "https://dustycloud.org/followers",
                "https://rhiaro.co.uk/followers/"
            ],
            "type" => "Like"
        ], $restoredJson);
    }

    public function testExample15(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Note",
  "content": "This is a note",
  "published": "2015-02-10T15:04:55Z",
  "to": ["https://example.org/~john/"],
  "cc": ["https://example.com/~erik/followers",
         "https://www.w3.org/ns/activitystreams#Public"]
}
JSON;

        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            "@context" => "https://www.w3.org/ns/activitystreams",
            "cc" => [
                "https://example.com/~erik/followers",
                "https://www.w3.org/ns/activitystreams#Public"
            ],
            "content" => "This is a note",
            "published" => "2015-02-10T15:04:55Z",
            "to" => "https://example.org/~john/",
            "type" => "Note"
        ], $restoredJson);
    }

    public function testExample17(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "id": "https://example.com/~alice/note/72",
  "type": "Tombstone",
  "published": "2015-02-10T15:04:55Z",
  "updated": "2015-02-10T15:04:55Z",
  "deleted": "2015-02-10T15:04:55Z"
}
JSON;

        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            "@context" => "https://www.w3.org/ns/activitystreams",
            "deleted" => "2015-02-10T15:04:55Z",
            "id" => "https://example.com/~alice/note/72",
            "published" => "2015-02-10T15:04:55Z",
            "type" => "Tombstone",
            "updated" => "2015-02-10T15:04:55Z"
        ], $restoredJson);
    }

    protected function restoreJson(string $json): array
    {
        $serializer = self::getService(SerializerInterface::class);

        $restoredJson = json_decode(
            $serializer->serialize(
                $serializer->deserialize($json, CoreType::class, ActivityStreamEncoder::FORMAT),
                ActivityStreamEncoder::FORMAT
            ),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        return $restoredJson;
    }
}
