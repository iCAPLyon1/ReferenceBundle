<?php

namespace ICAP\ReferenceBundle\Form\Reference;

use Symfony\Component\Form\FormBuilderInterface;

class EditReferenceType extends ReferenceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add(
            'customFields', 
            'collection', 
            array(
                'type' => new CustomFieldType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            )
        );
    }

    public function getName()
    {
        return 'icap_referencebundle_editreferencetype';
    }
}
