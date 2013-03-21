<?php

namespace ICAP\ReferenceBundle\Form\Reference;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'fieldKey',
                null,
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'attr' => array('placeholder' => 'key')
                )
            )
            ->add(
                'fieldValue',
                null,
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'attr' => array('placeholder' => 'value')
                )
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'ICAP\ReferenceBundle\Entity\CustomField',
            )
        );
    }

    public function getName()
    {
        return 'icap_referencebundle_customfieldtype';
    }
}
