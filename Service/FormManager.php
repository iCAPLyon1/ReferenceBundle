<?php

namespace ICAP\ReferenceBundle\Service;

use ICAP\ReferenceBundle\Form\Reference\ReferenceType;
use ICAP\ReferenceBundle\Form\Reference\EditReferenceType;
use ICAP\ReferenceBundle\Form\Reference\CustomFieldType;

class FormManager
{
    protected $container;

    public function __construct($container) 
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getFormFactory()
    {
        return $this->getContainer()->get('form.factory');
    }

    public function getReferencesConfiguration()
    {
        //return $this->getContainer()->getParameter('referencesConfiguration');

        $types = array(
            'default' => array('label' => 'other', 'dataType' => 'default', 'service' => 'icap_type.reference_default', 'icon' => 'icapreference/images/reference_default.png', 'amazon_search_category' => null),
            'bibliography' => array('label' => 'bibliography', 'dataType' => 'bibliography', 'service' => 'icap_type.reference_bibliography', 'icon' => 'icapreference/images/reference_book.png', 'amazon_search_category' => 'Books'),
            'filmography' => array('label' => 'filmography', 'dataType' => 'filmography', 'service' => 'icap_type.reference_filmography', 'icon' => 'icapreference/images/reference_film.png', 'amazon_search_category' => 'DVD'),
            'discography' => array('label' => 'discography', 'dataType' => 'discography', 'service' => 'icap_type.reference_discography', 'icon' => 'icapreference/images/reference_disc.png', 'amazon_search_category' => 'Music')
        );
        $referencesConfiguration = array('types' => $types);
        
        return $referencesConfiguration;
    }

    public function getForm($type, $reference = null)
    {
        return $this->getFormFactory()->create(
            new ReferenceType(),
            $reference,
            array(
                'dataType' => $this->getDataType($type)
            )
        ); 
    }

    public function getEditForm($type, $reference = null)
    {
        return $this->getFormFactory()->create(
            new EditReferenceType(),
            $reference,
            array(
                'dataType' => $this->getDataType($type)
            )
        ); 
    }

    public function getCustomForm($customField = null)
    {
        return $this->getFormFactory()->create(
            new CustomFieldType(),
            $customField
        ); 
    }

    public function getDataType($type)
    {
        $referencesConfiguration = $this->getReferencesConfiguration();
        $types = $referencesConfiguration['types'];

        return $types[$type]['dataType'];
    }

    public function getServiceName($type)
    {
        $referencesConfiguration = $this->getReferencesConfiguration();
        $types = $referencesConfiguration['types'];

        return $types[$type]['service'];
    }

    public function getIcon($type)
    {
        $referencesConfiguration = $this->getReferencesConfiguration();
        $types = $referencesConfiguration['types'];

        $icon = $types[$type]['icon'];
        if(!$icon) {
            $icon = 'icapreference/images/reference_default.png';
        }

        return $icon;
    }
}