<?php

namespace ICAP\ReferenceBundle\Form\Reference;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class FilmographyType extends AbstractReferenceDataExtractor
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('language', null, array('required' => false))
            ->add('director', null, array('required' => false))
            ->add('actors', null, array('required' => false))
            ->add('synopsis', 'textarea', array('required' => false))
            ->add('scenarist', null, array('required' => false))
            ->add('publisher', null, array('required' => false))
            ->add('producer', null, array('required' => false))
            ->add('releaseDate', null, array('required' => false))
            ->add('duration', 'integer', array('required' => false));
    }

    public function getName()
    {
        return 'filmography';
    }

    public function extractData(Request $request, $reference)
    {
        $reference = parent::extractData($request, $reference);

        $data = $reference->getData();

        $actors = $request->get('actors');
        if ($actors != null) {
            $data['actors'] = $actors;
        }

        $director = $request->get('director');
        if ($director != null) {
            $data['director'] = $director;
        }

        $publisher = $request->get('publisher');
        if ($publisher != null) {
            $data['publisher'] = $publisher;
        }

        $releaseDate = $request->get('releaseDate');
        if ($releaseDate != null) {
            $data['releaseDate'] = $releaseDate;
        }

        $reference->setData($data);

        return $reference;
    }
}