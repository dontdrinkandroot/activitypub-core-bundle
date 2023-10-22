<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance\Spec\Vocabulary;

use Dontdrinkandroot\ActivityPubCoreBundle\Tests\Acceptance\SerializationTestTrait;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\WebTestCase;

class ActorTypeTest extends WebTestCase
{
    use SerializationTestTrait;

    public function testApplication(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Application",
  "name": "Exampletron 3000"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'type' => 'Application',
            'name' => 'Exampletron 3000',
        ], $restoredJson);
    }

    public function testGroup(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Group",
  "name": "Big Beards of Austin"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'type' => 'Group',
            'name' => 'Big Beards of Austin',
        ], $restoredJson);
    }

    public function testOrganization(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Organization",
  "name": "Example Co."
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'type' => 'Organization',
            'name' => 'Example Co.',
        ], $restoredJson);
    }

    public function testPerson(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Person",
  "name": "Sally Smith"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'type' => 'Person',
            'name' => 'Sally Smith',
        ], $restoredJson);
    }

    public function testService(): void
    {
        $json = <<<JSON
{
  "@context": "https://www.w3.org/ns/activitystreams",
  "type": "Service",
  "name": "Acme Web Service"
}
JSON;
        $restoredJson = $this->restoreJson($json);
        $this->assertEquals([
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'type' => 'Service',
            'name' => 'Acme Web Service',
        ], $restoredJson);
    }
}
