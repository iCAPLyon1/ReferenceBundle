<?php

namespace ICAP\ReferenceBundle\DataFixtures;

use ICAP\ReferenceBundle\Entity\ReferenceBankOptions;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadOptionsData extends AbstractFixture
{
     /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $options = new ReferenceBankOptions();
        $options->setReferenceByPage(10);
        $manager->persist($options);
        $manager->flush();
    }
}
