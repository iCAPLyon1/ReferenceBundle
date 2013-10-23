<?php

namespace Icap\ReferenceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DeleteReferenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden', array('mapped' => false))
        ;
    }

        public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Icap\ReferenceBundle\Entity\Reference',
        ));
    }

    public function getName()
    {
        return 'icap_referencebundle_deletereferencetype';
    }
}
