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

    public function testEmptyWithAdminButtonsReferenceBank()
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

    public function testSuccessBibliographyCreation()
    {
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
        $this->assertEquals('/bundles/icapreference/images/reference_book.png', $crawler->filter('img.img-polaroid')->first()->attr('src'));
        $this->assertEquals(1, $crawler->filter('form#editReferenceForm')->count());
    }

    public function testSuccessDiscographyCreation()
    {
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
        $this->assertEquals('/bundles/icapreference/images/reference_disc.png', $crawler->filter('img.img-polaroid')->first()->attr('src'));
        $this->assertEquals(1, $crawler->filter('form#editReferenceForm')->count());
    }

    public function testSuccessFilmographyCreation()
    {
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
        $this->assertEquals('/bundles/icapreference/images/reference_film.png', $crawler->filter('img.img-polaroid')->first()->attr('src'));
        $this->assertEquals(1, $crawler->filter('form#editReferenceForm')->count());
    }

    public function testSuccessDefaultReferenceCreation()
    {
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
        $this->assertEquals('/bundles/icapreference/images/reference_default.png', $crawler->filter('img.img-polaroid')->first()->attr('src'));
        $this->assertEquals(1, $crawler->filter('form#editReferenceForm')->count());
    }

    public function testUpdateReference()
    {
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
    }

    public function testDeleteReference()
    {
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
    }

    /**
     * @group debug
     */
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
        $this->assertRegExp('/new key/', $crawler->filter('#icap_referencebundle_editreferencetype_customFields_0_fieldKey')->first()->attr('value'));
        $this->assertRegExp('/new value/', $crawler->filter('#icap_referencebundle_editreferencetype_customFields_0_fieldValue')->first()->text());

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
