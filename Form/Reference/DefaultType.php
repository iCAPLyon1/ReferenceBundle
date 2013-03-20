<?php

namespace ICAP\ReferenceBundle\Form\Reference;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class DefaultType extends AbstractReferenceDataExtractor
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('year', 'integer', array('required' => false))
            ->add('language', null, array('required' => false));
    }

    public function getName()
    {
        return 'default';
    }
}