<?php

namespace ICAP\ReferenceBundle\Tests\DataFixtures;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Claroline\CoreBundle\Library\Fixtures\LoggableFixture;
use ICAP\ReferenceBundle\Entity\ReferenceBank;
use ICAP\ReferenceBundle\Entity\Reference;

class LoadReferenceData extends LoggableFixture implements ContainerAwareInterface
{
    /** @var ContainerInterface $container */
    private $container;

    private $username;
    private $referenceBankName;
    private $nbReference;
    private $parent;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function __construct($referenceBankName, $username, $nbReference, $parent = null)
    {
        $this->referenceBankName = $referenceBankName;
        $this->username = $username;
        $this->nbReference = $nbReference;
        $this->parent = $parent;
    }

    public function load(ObjectManager $manager)
    {
        $creator = $this->getContainer()->get('claroline.resource.manager');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $user = $em->getRepository('ClarolineCoreBundle:User')->findOneBy(array('username' => $this->username));
        if ($this->parent == null) {
            $root = $em->getRepository('ClarolineCoreBundle:Resource\AbstractResource')
                ->findOneBy(array('parent' => null, 'workspace' => $user->getPersonalWorkspace()->getId()));
        } else {
            $root = $this->parent;
        }

        $referenceBank = new ReferenceBank();
        $referenceBank->setName($this->referenceBankName);
        $referenceBank = $creator->create($referenceBank, $root->getId(), 'icap_referencebank', $user);
        $this->log("referenceBank {$referenceBank->getName()} created");

        $referencesConfiguration = $this
            ->getContainer()
            ->get('icap_reference.form_manager')
            ->getReferencesConfiguration();

        $types = $referencesConfiguration['types'];
        $dataTypes = array();
        $iconNames = array();
        foreach ($types as $type) {
            $dataTypes[] = $type['dataType'];
            $iconNames[] = $type['icon'];
        }

        $maxOffset = count($dataTypes);
        $this->log("types found: ".count($dataTypes));
        $maxOffset--;

        $manager->persist($referenceBank);
        for ($i = 0; $i < $this->nbReference; $i++) {
            $title = $this->container->get('claroline.utilities.lipsum_generator')->generateLipsum(3);
            $description = $this->container->get('claroline.utilities.lipsum_generator')->generateLipsum(rand(50, 500));
            $reference = new Reference();
            $reference->setTitle($title);
            $reference->setDescription($description);

            $typeNumber = rand(0, $maxOffset);
            $reference->setIconName($iconNames[$typeNumber]);
            $reference->setType($dataTypes[$typeNumber]);

            $this->log("reference $title created");
            $referenceBank->addReference($reference);

            $manager->persist($reference);
            $manager->flush();

            $this->addReference("reference/{$i}", $reference);
        }
        $manager->flush();

        $this->addReference("referenceBank/{$this->referenceBankName}", $referenceBank);
    }
}