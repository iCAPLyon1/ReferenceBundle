<?php

namespace ICAP\ReferenceBundle\Form\Reference;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\AbstractType;

abstract class AbstractReferenceDataExtractor extends AbstractType
{
    public function extractData(Request $request, $reference) 
    {
        $imageUrl = $request->get('imageUrl');
        if($imageUrl != null) {
            $reference->setImageUrl($imageUrl);
        }

        $title = $request->get('title');
        if($title != null)
            $reference->setTitle($title);

        $reviewIndex = 0;
        $reviewSource = $request->get('reviewSource_'.$reviewIndex);
        $reviewContent = $request->get('reviewContent_'.$reviewIndex);
        
        $reviewIndex++;

        while($reviewSource != null && $reviewContent != null) {
            $reference->setCustomFieldByKey('Description from '.$reviewSource, $reviewContent);

            $reviewSource = $request->get('reviewSource_'.$reviewIndex);
            $reviewContent = $request->get('reviewContent_'.$reviewIndex);

            $reviewIndex++;
        }

        return $reference;
    }
}