<?php

namespace Icap\ReferenceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ReferenceBankOptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('referenceByPage', 'integer');
        $builder->add('amazonApiKey');
        $builder->add('amazonSecretKey');
        $builder->add('amazonAssociateTag');
        $builder->add('amazonCountry', 'choice', array(
            'choices' => array(
                'fr' => 'fr', 
                'com' => 'com', 
                'co.uk' => 'co.uk', 
                'de' => 'de', 
                'ca' => 'ca', 
                'co.jp' => 'co.jp', 
                'it' => 'it', 
                'cn' => 'cn', 
                'es' => 'es'
            )
        ));
    }

    public function getName()
    {
        return 'icap_referencebank_options_form';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'translation_domain' => 'icap_referencebank'
        );
    }
}