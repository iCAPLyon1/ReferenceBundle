<?php

namespace ICAP\ReferenceBundle\Controller;

use Claroline\CoreBundle\Library\Testing\FunctionalTestCase;
use ICAP\ReferenceBundle\Tests\DataFixtures\LoadReferenceData;
use ICAP\ReferenceBundle\DataFixtures\LoadOptionsData;

class ReferenceControllerTest extends FunctionalTestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->loadPlatformRoleData();
        $this->loadUserData(array('user' => 'user', 'ws_creator' => 'ws_creator', 'admin' => 'admin'));
        $this->client->followRedirects();
    }

    public function testWrongReferenceBankURL()
    {
        $crawler = $this->client->request('GET', '/reference');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("404")')->count());
    }

    public function testEmptyReferenceBank()
    {
        $this->loadFixture(new LoadOptionsData());
        $this->loadFixture(new LoadReferenceData('test', 'ws_creator', 0));
        $this->logUser($this->getUser('ws_creator'));

        $referenceBank = $this->getFixtureReference('referenceBank/test');
        $resourceId = $referenceBank->getId();

        $crawler = $this->client
            ->request('GET', "/reference/".$resourceId);

        $this->assertGreaterThan(0, $crawler->filter('div.section-content')->count());
        $this->assertGreaterThan(0, $crawler->filter('div.reference-content')->count());
        $this->assertGreaterThan(0, $crawler->filter('a.new-reference')->count());
    }
}
