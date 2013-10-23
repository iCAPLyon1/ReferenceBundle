<?php

namespace Icap\ReferenceBundle\Entity;

use Claroline\CoreBundle\Entity\Resource\AbstractResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="icap__referencebundle_referencebank")
 */
class ReferenceBank extends AbstractResource
{
     /**
     * @ORM\OneToMany(targetEntity="Icap\ReferenceBundle\Entity\Reference", mappedBy="referenceBank", cascade={"persist"}, orphanRemoval=true)
     */
    protected $references;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->references = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add reference
     *
     * @param \Icap\ReferenceBundle\Entity\Reference $reference
     * @return ReferenceBank
     */
    public function addReference(\Icap\ReferenceBundle\Entity\Reference $reference)
    {
        $customField->setReferenceBank($this);
        $this->references[] = $reference;
    
        return $this;
    }

    /**
     * Remove reference
     *
     * @param \Icap\ReferenceBundle\Entity\Reference $reference
     */
    public function removeReference(\Icap\ReferenceBundle\Entity\Reference $reference)
    {
        $this->references->removeElement($reference);
    }

    /**
     * Get customFields
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReferences()
    {
        return $this->references;
    }
}