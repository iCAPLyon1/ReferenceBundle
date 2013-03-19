<?php

namespace ICAP\ReferenceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ReferenceBankType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
    }

    public function getName()
    {
        return 'icap_referencebank_form';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'translation_domain' => 'icap_referencebank'
        );
    }
}