<?php

namespace ICAP\ReferenceBundle\Form\Reference;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class DiscographyType extends AbstractReferenceDataExtractor
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('releaseDate', null, array('required' => false))
            ->add('language', null, array('required' => false))
            ->add('artist', null, array('required' => false))
            ->add('composer', null, array('required' => false))
            ->add('label', null, array('required' => false))
            ->add('duration', null, array('required' => false));
    }

    public function getName()
    {
        return 'discography';
    }

    public function extractData(Request $request, $reference)
    {
        $reference = parent::extractData($request, $reference);

        $data = $reference->getData();

        $artist = $request->get('artist');
        if ($artist != null) {
            $data['artist'] = $artist;    
        }

        $label = $request->get('label');
        if ($label != null) {
            $data['label'] = $label;
        }

        $releaseDate = $request->get('releaseDate');
        if ($releaseDate != null) {
            $data['releaseDate'] = $releaseDate;
        }

        $reference->setData($data);

        return $reference;
    }
}