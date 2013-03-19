<?php

namespace ICAP\ReferenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="icap__referencebundle_referencebankoptions")
 */
class ReferenceBankOptions
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $referenceByPage = 10;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $amazonApiKey;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $amazonSecretKey;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $amazonAssociateTag;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $amazonCountry;

    public function getId()
    {
        return $this->id;
    }

    public function setReferenceByPage($referenceByPage)
    {
        $this->referenceByPage = $referenceByPage;
    }

    public function getReferenceByPage()
    {
        return $this->referenceByPage;
    }

    public function setAmazonApiKey($amazonApiKey)
    {
        $this->amazonApiKey = $amazonApiKey;
    }

    public function getAmazonApiKey()
    {
        return $this->amazonApiKey;
    }

    public function setAmazonSecretKey($amazonSecretKey)
    {
        $this->amazonSecretKey = $amazonSecretKey;
    }

    public function getAmazonSecretKey()
    {
        return $this->amazonSecretKey;
    }

    public function setAmazonAssociateTag($amazonAssociateTag)
    {
        $this->amazonAssociateTag = $amazonAssociateTag;
    }

    public function getAmazonAssociateTag()
    {
        return $this->amazonAssociateTag;
    }

    public function setAmazonCountry($amazonCountry)
    {
        $this->amazonCountry = $amazonCountry;
    }

    public function getAmazonCountry()
    {
        return $this->amazonCountry;
    }
}