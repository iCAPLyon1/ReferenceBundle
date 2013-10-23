<?php

namespace Icap\ReferenceBundle\Form\Reference;

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
            ->add('url', 'hidden')
        ;

        if($options['dataType']) {
            $builder
                ->add('data', $options['dataType'])
            ;
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Icap\ReferenceBundle\Entity\Reference',
            'dataType' => null,
        ));
    }

    public function getName()
    {
        return 'icap_referencebundle_referencetype';
    }
}
