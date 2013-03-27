<?php

namespace ICAP\ReferenceBundle\Entity;

use Claroline\CoreBundle\Entity\Resource\AbstractResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="icap__referencebundle_referencebank")
 */
class ReferenceBank extends AbstractResource
{
     /**
     * @ORM\OneToMany(
     *      targetEntity="ICAP\ReferenceBundle\Entity\Reference",
     *      mappedBy="referenceBank",
     *      cascade={"all"},
     *      orphanRemoval=true
     * )
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
     * @param \ICAP\ReferenceBundle\Entity\Reference $reference
     * @return ReferenceBank
     */
    public function addReference(\ICAP\ReferenceBundle\Entity\Reference $reference)
    {
        $reference->setReferenceBank($this);
        $this->references[] = $reference;

        return $this;
    }

    /**
     * Remove reference
     *
     * @param \ICAP\ReferenceBundle\Entity\Reference $reference
     */
    public function removeReference(\ICAP\ReferenceBundle\Entity\Reference $reference)
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

    public function getPathArray()
    {
        $path = $this->getPath();
        $pathItems = explode("`", $path);
        $pathArray = array();
        foreach ($pathItems as $item) {
            preg_match("/-([0-9]+)$/", $item, $matches);
            if (count($matches) > 0) {
                $id = substr($matches[0], 1);
                $name = preg_replace("/-([0-9]+)$/", "", $item);
                $pathArray[] = array('id' => $id, 'name' => $name);
            }
        }

        return $pathArray;
    }
}