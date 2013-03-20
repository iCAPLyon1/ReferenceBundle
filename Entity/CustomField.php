<?php

namespace ICAP\ReferenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="icap__referencebundle_customfield")
 */
class CustomField
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $fieldKey;

    /**
     * @ORM\Column(type="text")
     */
    protected $fieldValue;


    /**
     * @ORM\ManyToOne(targetEntity="ICAP\ReferenceBundle\Entity\Reference", inversedBy="customFields")
     * @ORM\JoinColumn(name="reference_id", referencedColumnName="id")
     */
    protected $reference;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set reference
     *
     * @param \ICAP\ReferenceBundle\Entity\Reference $reference
     * @return CustomField
     */
    public function setReference(\ICAP\ReferenceBundle\Entity\Reference $reference = null)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return \ICAP\ReferenceBundle\Entity\Reference
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set fieldKey
     *
     * @param string $fieldKey
     * @return CustomField
     */
    public function setFieldKey($fieldKey)
    {
        $this->fieldKey = $fieldKey;

        return $this;
    }

    /**
     * Get fieldKey
     *
     * @return string 
     */
    public function getFieldKey()
    {
        return $this->fieldKey;
    }

    /**
     * Set fieldValue
     *
     * @param string $fieldValue
     * @return CustomField
     */
    public function setFieldValue($fieldValue)
    {
        $this->fieldValue = $fieldValue;

        return $this;
    }

    /**
     * Get fieldValue
     *
     * @return string 
     */
    public function getFieldValue()
    {
        return $this->fieldValue;
    }
}