<?php

namespace ICAP\ReferenceBundle\Controller;

use Claroline\CoreBundle\Library\Testing\FunctionalTestCase;
use ICAP\ReferenceBundle\Tests\DataFixtures\LoadReferenceData;
use ICAP\ReferenceBundle\DataFixtures\LoadOptionsData;

class ReferenceControllerTest extends FunctionalTestCase
{
    private $logRepository;

    public function setUp()
    {
        parent::setUp();
        $this->loadPlatformRoleData();
        $this->loadUserData(array('user' => 'user', 'ws_creator' => 'ws_creator', 'admin' => 'admin'));
        $this->client->followRedirects();
        $this->logRepository = $this->em->getRepository('ClarolineCoreBundle:Logger\Log');
    }

    public function testWrongReferenceBankURL()
    {
        $now = new \DateTime();

        $crawler = $this->client->request('GET', '/reference');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("404")')->count());

        $logs = $this->logRepository->findActionAfterDate(
            'resource_read',
            $now,
            $this->getUser('ws_creator')->getId()
        );
        $this->assertEquals(0, count($logs));
    }

    public function testEmptyWithAdminButtonsReferenceBank()
    {
        $now = new \DateTime();

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

        $logs = $this->logRepository->findActionAfterDate(
            'resource_read',
            $now,
            $this->getUser('ws_creator')->getId(),
            $resourceId
        );
        $this->assertEquals(1, count($logs));
    }

    public function testSuccessBibliographyCreation()
    {
        $now = new \DateTime();

        $this->loadFixture(new LoadOptionsData());
        $this->loadFixture(new LoadReferenceData('test', 'ws_creator', 0));
        $this->logUser($this->getUser('ws_creator'));

        $referenceBank = $this->getFixtureReference('referenceBank/test');
        $resourceId = $referenceBank->getId();

        $crawler = $this->client
            ->request('GET', "/reference/".$resourceId."/new_light");

        $formCrawler = $crawler->selectButton('createNewReferenceSubmit');
        $form = $formCrawler->form();
        $form['icap_referencebundle_choosereferencetype[type]']->select('bibliography');

        // submit the form
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('div.section-content')->count());
        $this->assertEquals(
            '/bundles/icapreference/images/reference_book.png',
            $crawler->filter('img.img-polaroid')->first()->attr('src')
        );
        $this->assertEquals(1, $crawler->filter('form#editReferenceForm')->count());

        $logs = $this->logRepository->findActionAfterDate(
            'resource_child_update',
            $now,
            $this->getUser('ws_creator')->getId(),
            $resourceId,
            null,
            null,
            null,
            null,
            null,
            null,
            'icap_reference',
            'child_action_create'
        );
        $this->assertEquals(1, count($logs));
    }
 
    public function testSuccessDiscographyCreation()
    {
        $now = new \DateTime();

        $this->loadFixture(new LoadOptionsData());
        $this->loadFixture(new LoadReferenceData('test', 'ws_creator', 0));
        $this->logUser($this->getUser('ws_creator'));

        $referenceBank = $this->getFixtureReference('referenceBank/test');
        $resourceId = $referenceBank->getId();

        $crawler = $this->client
            ->request('GET', "/reference/".$resourceId."/new_light");

        $formCrawler = $crawler->selectButton('createNewReferenceSubmit');
        $form = $formCrawler->form();
        $form['icap_referencebundle_choosereferencetype[type]']->select('discography');

        // submit the form
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('div.section-content')->count());
        $this->assertEquals(
            '/bundles/icapreference/images/reference_disc.png',
            $crawler->filter('img.img-polaroid')->first()->attr('src')
        );
        $this->assertEquals(1, $crawler->filter('form#editReferenceForm')->count());

        $logs = $this->logRepository->findActionAfterDate(
            'resource_child_update',
            $now,
            $this->getUser('ws_creator')->getId(),
            $resourceId,
            null,
            null,
            null,
            null,
            null,
            null,
            'icap_reference',
            'child_action_create'
        );
        $this->assertEquals(1, count($logs));
    }

    public function testSuccessFilmographyCreation()
    {
        $now = new \DateTime();

        $this->loadFixture(new LoadOptionsData());
        $this->loadFixture(new LoadReferenceData('test', 'ws_creator', 0));
        $this->logUser($this->getUser('ws_creator'));

        $referenceBank = $this->getFixtureReference('referenceBank/test');
        $resourceId = $referenceBank->getId();

        $crawler = $this->client
            ->request('GET', "/reference/".$resourceId."/new_light");

        $formCrawler = $crawler->selectButton('createNewReferenceSubmit');
        $form = $formCrawler->form();
        $form['icap_referencebundle_choosereferencetype[type]']->select('filmography');

        // submit the form
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('div.section-content')->count());
        $this->assertEquals(
            '/bundles/icapreference/images/reference_film.png',
            $crawler->filter('img.img-polaroid')->first()->attr('src')
        );
        $this->assertEquals(1, $crawler->filter('form#editReferenceForm')->count());

        $logs = $this->logRepository->findActionAfterDate(
            'resource_child_update',
            $now,
            $this->getUser('ws_creator')->getId(),
            $resourceId,
            null,
            null,
            null,
            null,
            null,
            null,
            'icap_reference',
            'child_action_create'
        );
        $this->assertEquals(1, count($logs));
    }

    public function testSuccessDefaultReferenceCreation()
    {
        $now = new \DateTime();

        $this->loadFixture(new LoadOptionsData());
        $this->loadFixture(new LoadReferenceData('test', 'ws_creator', 0));
        $this->logUser($this->getUser('ws_creator'));

        $referenceBank = $this->getFixtureReference('referenceBank/test');
        $resourceId = $referenceBank->getId();

        $crawler = $this->client
            ->request('GET', "/reference/".$resourceId."/new_light");

        $formCrawler = $crawler->selectButton('createNewReferenceSubmit');
        $form = $formCrawler->form();
        $form['icap_referencebundle_choosereferencetype[type]']->select('default');

        // submit the form
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('div.section-content')->count());
        $this->assertEquals(
            '/bundles/icapreference/images/reference_default.png',
            $crawler->filter('img.img-polaroid')->first()->attr('src')
        );
        $this->assertEquals(1, $crawler->filter('form#editReferenceForm')->count());

        $logs = $this->logRepository->findActionAfterDate(
            'resource_child_update',
            $now,
            $this->getUser('ws_creator')->getId(),
            $resourceId,
            null,
            null,
            null,
            null,
            null,
            null,
            'icap_reference',
            'child_action_create'
        );
        $this->assertEquals(1, count($logs));
    }

    public function testUpdateReference()
    {
        $now = new \DateTime();

        $this->loadFixture(new LoadOptionsData());
        $this->loadFixture(new LoadReferenceData('test', 'ws_creator', 1));
        $this->logUser($this->getUser('ws_creator'));

        $referenceBank = $this->getFixtureReference('referenceBank/test');
        $reference = $this->getFixtureReference('reference/0');

        $crawler = $this->client
            ->request('GET', "/reference/".$referenceBank->getId()."/edit/".$reference->getId());

        $formCrawler = $crawler->selectButton('editReferenceSubmit');
        $form = $formCrawler->form();
        $form['icap_referencebundle_editreferencetype[title]'] = 'Update success';

        // submit the form
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('div.section-content')->count());

        $this->assertRegExp('/Update success/', $crawler->filter('h3')->first()->text());

        $logs = $this->logRepository->findActionAfterDate(
            'resource_child_update',
            $now,
            $this->getUser('ws_creator')->getId(),
            $referenceBank->getId(),
            null,
            null,
            null,
            null,
            null,
            null,
            'icap_reference',
            'child_action_update'
        );
        $this->assertEquals(1, count($logs));
    }

    public function testDeleteReference()
    {
        $now = new \DateTime();

        $this->loadFixture(new LoadOptionsData());
        $this->loadFixture(new LoadReferenceData('test', 'ws_creator', 1));
        $this->logUser($this->getUser('ws_creator'));

        $referenceBank = $this->getFixtureReference('referenceBank/test');
        $reference = $this->getFixtureReference('reference/0');

        $this->assertEquals(1, count($referenceBank->getReferences()));

        $crawler = $this->client
            ->request('GET', "/reference/".$referenceBank->getId()."/delete/".$reference->getId());

        $formCrawler = $crawler->selectButton('deleteReferenceSubmit');
        $form = $formCrawler->form();

        // submit the form
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('div.section-content')->count());

        $this->em->refresh($referenceBank);
        $this->assertEquals(0, count($referenceBank->getReferences()));

        $logs = $this->logRepository->findActionAfterDate(
            'resource_child_update',
            $now,
            $this->getUser('ws_creator')->getId(),
            $referenceBank->getId(),
            null,
            null,
            null,
            null,
            null,
            null,
            'icap_reference',
            'child_action_delete'
        );
        $this->assertEquals(1, count($logs));
    }

    public function testCustomField()
    {
        $this->loadFixture(new LoadOptionsData());
        $this->loadFixture(new LoadReferenceData('test', 'ws_creator', 1));
        $this->logUser($this->getUser('ws_creator'));

        $referenceBank = $this->getFixtureReference('referenceBank/test');
        $reference = $this->getFixtureReference('reference/0');

        $this->assertEquals(1, count($referenceBank->getReferences()));

        $crawler = $this->client
            ->request('GET', "/reference/".$referenceBank->getId()."/new_custom_field/".$reference->getId());

        $formCrawler = $crawler->selectButton('newCustomFieldSubmit');
        $form = $formCrawler->form();
        $form['icap_referencebundle_customfieldtype[fieldKey]'] = 'new key';
        $form['icap_referencebundle_customfieldtype[fieldValue]'] = 'new value';

        // submit the form
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertRegExp(
            '/new key/',
            $crawler
                ->filter('#icap_referencebundle_editreferencetype_customFields_0_fieldKey')
                ->first()
                ->attr('value')
        );
        $this->assertRegExp(
            '/new value/',
            $crawler
                ->filter('#icap_referencebundle_editreferencetype_customFields_0_fieldValue')
                ->first()
                ->text()
        );

        $this->em->refresh($reference);
        $this->assertEquals(1, count($reference->getCustomFields()));
        $customField = $reference->getCustomFields()[0];

        $crawler = $this->client
            ->request('GET', "/reference/".$referenceBank->getId()."/delete_custom_field/".$customField->getId());

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->em->refresh($reference);
        $this->assertEquals(0, count($reference->getCustomFields()));
    }
}
