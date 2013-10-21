<?php

namespace Icap\ReferenceBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Pagerfanta\Adapter\AdapterInterface;

class AmazonECSAdapter implements AdapterInterface
{
    protected $amazonEcs;
    protected $search;

    public function __construct(AmazonECS $amazonEcs, $search) 
    {
        $this->amazonEcs = $amazonEcs;
        $this->search = $search;
    }

    /**
     * Returns the number of results.
     *
     * @return integer The number of results.
     *
     * @api
     */
    public function getNbResults()
    {  
        $this->amazonEcs->returnType(AmazonECS::RETURN_TYPE_ARRAY);
        $searchResult = $this->amazonEcs->search($this->search);

        if($searchResult['Items']['Request']['IsValid'] == 'True') {
            $totalResults = $searchResult['Items']['TotalResults'];

            //Amazon restriction
            if($totalResults > 100) {
                $totalResults = 100;
            }

            return $totalResults;
        } else {
            throw new NotFoundHttpException();
        }
    }

    /**
     * Returns an slice of the results.
     *
     * @param integer $offset The offset.
     * @param integer $length The length.
     *
     * @return array|\Iterator|\IteratorAggregate The slice.
     *
     * @api
     */
    public function getSlice($offset, $length) 
    {
        // We can"t change number item by page.
        $length = 10;

        $page = 1;
        if($offset != 0) {
            $page = ($offset / 10)+1;
        }

        $this->amazonEcs->page($page);

        $this->amazonEcs->returnType(AmazonECS::RETURN_TYPE_ARRAY);
        $searchResult = $this->amazonEcs->search($this->search);

        if($searchResult['Items']['Request']['IsValid'] == 'True') {

            return $searchResult['Items']['Item'];
        } else {
            throw new NotFoundHttpException();
        }
    }
}
