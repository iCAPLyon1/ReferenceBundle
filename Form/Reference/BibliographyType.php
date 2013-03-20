<?php

namespace ICAP\ReferenceBundle\Form\Reference;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class BibliographyType extends AbstractReferenceDataExtractor
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('publicationDate', null, array('required' => false))
            ->add('language', null, array('required' => false))
            ->add('author', null, array('required' => false))
            ->add('summary', 'textarea', array('required' => false))
            ->add('isbn', null, array('required' => false))
            ->add('publisher', null, array('required' => false))
            ->add('pages', null, array('required' => false));
    }

    public function getName()
    {
        return 'bibliography';
    }

    public function extractData(Request $request, $reference)
    {
        $reference = parent::extractData($request, $reference);

        $data = $reference->getData();

        $author = $request->get('author');
        if ($author != null) {
            $data['author'] = $author;
        }

        $publicationDate = $request->get('publicationDate');
        if ($publicationDate != null) {
            $data['publicationDate'] = $publicationDate;
        }

        $editor = $request->get('editor');
        if ($editor != null) {
            $data['editor'] = $editor;
        }

        $isbn = $request->get('isbn');
        if ($isbn != null) {
            $data['isbn'] = $isbn;
        }

        $reference->setData($data);

        return $reference;
    }
}