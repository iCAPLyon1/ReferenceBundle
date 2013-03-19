<?php

namespace ICAP\ReferenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="icap__referencebundle_reference")
 */
class Reference
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
    protected $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $imageUrl;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $type;

    /**
     * Source of the reference
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $iconName;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $data;

    /**
     * @ORM\OneToMany(targetEntity="ICAP\ReferenceBundle\Entity\CustomField", mappedBy="reference", cascade={"all"}, orphanRemoval=true)
     */
    protected $customFields;

    /**
     * @ORM\ManyToOne(targetEntity="ICAP\ReferenceBundle\Entity\ReferenceBank", inversedBy="references")
     * @ORM\JoinColumn(name="referencebank_id", referencedColumnName="id")
     */
    protected $referenceBank;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customFields = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set title
     *
     * @param string $title
     * @return Reference
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Set imageUrl
     *
     * @param string $imageUrl
     * @return Reference
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
    
        return $this;
    }

    /**
     * Get imageUrl
     *
     * @return string 
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Reference
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Reference
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Reference
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set iconName
     *
     * @param string $iconName
     * @return Reference
     */
    public function setIconName($iconName)
    {
        $this->iconName = $iconName;
    
        return $this;
    }

    /**
     * Get iconName
     *
     * @return string 
     */
    public function getIconName()
    {
        return $this->iconName;
    }

    /**
     * Set data
     *
     * @param array $data
     * @return Reference
     */
    public function setData($data)
    {
        $this->data = $data;
    
        return $this;
    }

    /**
     * Get data
     *
     * @return array 
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * Add customFields
     *
     * @param \ICAP\ReferenceBundle\Entity\CustomField $customField
     * @return Reference
     */
    public function addCustomField(\ICAP\ReferenceBundle\Entity\CustomField $customField)
    {
        $customField->setReference($this);
        $this->customFields[] = $customField;
    
        return $this;
    }

    /**
     * Remove customFields
     *
     * @param \ICAP\ReferenceBundle\Entity\CustomField $customField
     */
    public function removeCustomField(\ICAP\ReferenceBundle\Entity\CustomField $customField)
    {
        $this->customFields->removeElement($customField);
    }

    /**
     * Get customFields
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomFields()
    {
        return $this->customFields;
    }

    /**
     * Get customField by fieldKey
     *
     * @return \ICAP\ReferenceBundle\Entity\CustomField $customField or null 
     */
    public function getCustomFieldByKey($fieldKey) {
        foreach ($this->getCustomFields() as $customField) {
            if($customField->getFieldKey() == $fieldKey) {

                return $customField;
            }
        }

        return null;
    }

    /**
     * Set customField by key or add if not exist
     *
     * @param $fieldKey, $fieldValue
     * @return Reference
     */
    public function setCustomFieldByKey($fieldKey, $fieldValue) {
        $customField = $this->getCustomFieldByKey($fieldKey);
        if($customField == null) {
            $customField = new CustomField();
            $customField->setFieldKey($fieldKey);
            $this->addCustomField($customField);
        }
        $customField->setFieldValue($fieldValue);

        return $this;
    }

    /**
     * Set referenceBank
     *
     * @param \ICAP\ReferenceBundle\Entity\ReferenceBank $referenceBank
     * @return Reference
     */
    public function setReferenceBank(\ICAP\ReferenceBundle\Entity\ReferenceBank $referenceBank = null)
    {
        $this->referenceBank = $referenceBank;
    
        return $this;
    }

    /**
     * Get referenceBank
     *
     * @return \ICAP\ReferenceBundle\Entity\ReferenceBank 
     */
    public function getReferenceBank()
    {
        return $this->referenceBank;
    }
}