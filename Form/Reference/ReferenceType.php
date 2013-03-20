<?php

namespace ICAP\ReferenceBundle\Form\Reference;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReferenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('imageUrl')
            ->add('description')
            ->add('type', 'hidden')
            ->add('url', 'hidden');

        if($options['dataType']) {
            $builder
                ->add('data', $options['dataType'], array('label_attr' => array('style' => 'display:none')));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ICAP\ReferenceBundle\Entity\Reference',
            'dataType' => null,
        ));
    }

    public function getName()
    {
        return 'icap_referencebundle_referencetype';
    }
}
