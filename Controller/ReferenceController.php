<?php

namespace ICAP\ReferenceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Claroline\CoreBundle\Library\Resource\ResourceCollection;
use ICAP\ReferenceBundle\Form\DeleteReferenceType;
use ICAP\ReferenceBundle\Form\ReferenceBankOptionsType;
use ICAP\ReferenceBundle\Entity\Reference;
use ICAP\ReferenceBundle\Entity\ReferenceBankOptions;
use ICAP\ReferenceBundle\Entity\CustomField;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Claroline\CoreBundle\Library\Event\LogResourceReadEvent;
use Claroline\CoreBundle\Library\Event\LogResourceChildUpdateEvent;

class ReferenceController extends Controller
{
    const REFERENCE_CHILD_TYPE = 'icap_reference';

    protected function isAllow($referenceBank, $actionName)
    {
        $collection = new ResourceCollection(array($referenceBank));
        if (false === $this->get('security.context')->isGranted($actionName, $collection)) {
            throw new AccessDeniedException();
        }
    }

    protected function isAllowToEdit($referenceBank)
    {
        $this->isAllow($referenceBank, 'EDIT');
    }

    protected function isAllowToShow($referenceBank)
    {
        $this->isAllow($referenceBank, 'OPEN');
    }

    protected function getOptions()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $options = $em->getRepository('ICAPReferenceBundle:ReferenceBankOptions')->findAll();

        if ($options != null) {
            $options = $options[0];
        }
        if ($options == null) {
            $options = new ReferenceBankOptions();
        }

        return $options;
    }

    protected function isOptionsSet()
    {
        $options = $this->getOptions();

        return ($options->getAmazonApiKey() != null && $options->getAmazonApiKey() != '')
            && ($options->getAmazonSecretKey() != null && $options->getAmazonSecretKey() != '')
            && ($options->getAmazonAssociateTag() != null && $options->getAmazonAssociateTag() != '')
            && (
                $options->getAmazonCountry() != null
                && $options->getAmazonCountry() != ''
                && in_array(
                    $options->getAmazonCountry(),
                    array(
                        'fr',
                        'com',
                        'co.uk',
                        'de',
                        'ca',
                        'co.jp',
                        'it',
                        'cn',
                        'es'
                    )
                )
            );
    }

    protected function getReferenceBank($referenceBankId)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $referenceBank = $em
            ->getRepository('ICAPReferenceBundle:ReferenceBank')
            ->findOneBy(array('id' => $referenceBankId));

        if ($referenceBank == null) {
            throw new NotFoundHttpException();
        }

        //$this->isAllowToShow($referenceBank);

        return $referenceBank;
    }

    protected function getReference($referenceId)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $reference = $em
            ->getRepository('ICAPReferenceBundle:Reference')
            ->findOneBy(array('id' => $referenceId ));
        if (!$reference) {
            throw $this->createNotFoundException('The reference does not exist');
        }

        return $reference;
    }

    protected function containsCustomField($customField, $customFields)
    {
        foreach ($customFields as $current) {
            if ($customField->getId() == $current->getId()) {

                return true;
            }
        }

        return false;
    }

    protected function customFieldToArray($customField)
    {
        return array('fieldKey' => $customField->getFieldKey(), 'fieldValue' => $customField->getFieldValue());
    }

    /**
     * @Route(
     *      "/{resourceId}",
     *      name="icap_reference_list", 
     *      requirements={"resourceId" = "\d+"},
     *      defaults={"page" = 1}
     * )
     * @Route(
     *      "/{resourceId}/{page}",
     *      name="icap_reference_list_paginated",
     *      requirements={"resourceId" = "\d+", "page" = "\d+"}, defaults={"page" = 1}
     * )
     * @Template()
     */
    public function listAction($resourceId, $page)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $referenceBank = $this->getReferenceBank($resourceId);
        $repository = $em->getRepository('ICAPReferenceBundle:Reference');

        $query = $repository
            ->createQueryBuilder('reference')
            ->andWhere('reference.referenceBank = :referenceBank')
            ->setParameter('referenceBank', $referenceBank)
            ->orderBy('reference.id', 'ASC');

        $adapter = new DoctrineORMAdapter($query);
        $pager   = new PagerFanta($adapter);

        //$pager->setMaxPerPage($this->container->getParameter('nb_reference_by_page'));
        $pager->setMaxPerPage($this->getOptions()->getReferenceByPage());

        try {
            $pager->setCurrentPage($page);
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
        }

        $log = new LogResourceReadEvent($referenceBank);
        $this->get('event_dispatcher')->dispatch('log', $log);

        return array(
            'pager' => $pager,
            'workspace' => $referenceBank->getWorkspace(),
            'pathArray' => $referenceBank->getPathArray(),
            'referenceBank' => $referenceBank,
            'resourceCollection' => new ResourceCollection(array($referenceBank))
        );
    }

    /**
     * @Route(
     *      "/{resourceId}/show/{id}",
     *      requirements={"resourceId" = "\d+", "id" = "\d+"},
     *      name="icap_reference_show"
     * )
     * @Template()
     */
    public function showAction($resourceId, $id)
    {
        $referenceBank = $this->getReferenceBank($resourceId);
        $reference = $this->getReference($id);

        $log = new LogResourceReadEvent($referenceBank);
        $this->get('event_dispatcher')->dispatch('log', $log);

        return array(
            'referenceBank' => $referenceBank,
            'workspace' => $referenceBank->getWorkspace(),
            'pathArray' => $referenceBank->getPathArray(),
            'reference' => $reference,
            'resourceCollection' => new ResourceCollection(array($referenceBank))
        );
    }

    /**
     * @Route(
     *      "/{resourceId}/edit/{id}",
     *      requirements={"resourceId" = "\d+", "id" = "\d+"},
     *      name="icap_reference_edit"
     * )
     * @Template()
     */
    public function editAction($resourceId, $id)
    {
        $referenceBank = $this->getReferenceBank($resourceId);
        $this->isAllowToEdit($referenceBank);

        $reference = $this->getReference($id);

        $form = $this->get('icap_reference.form_manager')->getEditForm(
            $reference->getType(),
            $reference
        );

        $referencesConfiguration = $this
            ->get('icap_reference.form_manager')
            ->getReferencesConfiguration();
        $types = $referencesConfiguration['types'];
        $searchCategory = $types[$reference->getType()]['amazon_search_category'];

        if (!$this->isOptionsSet()) {
            $searchCategory = null;
        }

        $log = new LogResourceReadEvent($referenceBank);
        $this->get('event_dispatcher')->dispatch('log', $log);

        return array(
            'referenceBank' => $referenceBank,
            'workspace' => $referenceBank->getWorkspace(),
            'pathArray' => $referenceBank->getPathArray(),
            'reference' => $reference,
            'form' => $form->createView(),
            'searchCategory' => $searchCategory
        );
    }

    /**
     * @Route(
     *      "/{resourceId}/update/{id}",
     *      requirements={"resourceId" = "\d+", "id" = "\d+"},
     *      name="icap_reference_update"
     * )
     * @Template()
     */
    public function updateAction(Request $request, $resourceId, $id)
    {
        $referenceBank = $this->getReferenceBank($resourceId);
        $this->isAllowToEdit($referenceBank);

        $reference = $this->getReference($id);

        $form = $this->get('icap_reference.form_manager')->getEditForm(
            $reference->getType(),
            $reference
        );
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($reference);
            $em->flush();

            $details = array(
                'icap_reference' => array(
                    'id' => $id,
                    'title' => $reference->getTitle(),
                    'type' => $reference->getType()
                )
            );
            $log = new LogResourceChildUpdateEvent($referenceBank, self::REFERENCE_CHILD_TYPE, LogResourceChildUpdateEvent::CHILD_ACTION_UPDATE, $details);
            $this->get('event_dispatcher')->dispatch('log', $log);

            return $this->redirect(
                $this->generateUrl(
                    'icap_reference_show',
                    array(
                        'resourceId' => $referenceBank->getId(),
                        'id' => $reference->getId()
                    )
                )
            );
        }

        return array(
            'referenceBank' => $referenceBank,
            'workspace' => $referenceBank->getWorkspace(),
            'reference' => $reference,
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *      "/{resourceId}/new_light",
     *      requirements={"resourceId" = "\d+"},
     *      name="icap_reference_new_light"
     * )
     * @Template()
     */
    public function newLightAction(Request $request, $resourceId)
    {
        $referenceBank = $this->getReferenceBank($resourceId);
        $this->isAllowToEdit($referenceBank);

        $form = $this->createForm($this->get('icap_reference.choose_type'));

        if ($request->isXMLHttpRequest()) {
            $serviceFormManager = $this->container->get('icap_reference.form_manager');
            $referencesConfiguration = $serviceFormManager->getReferencesConfiguration();

            return $this->render(
                'ICAPReferenceBundle:Reference:newLightModal.html.twig',
                array(
                    'referenceBank' => $referenceBank,
                    'form' => $form->createView(),
                    'workspace' => $referenceBank->getWorkspace()
                )
            );
        }

        $log = new LogResourceReadEvent($referenceBank);
        $this->get('event_dispatcher')->dispatch('log', $log);

        return array(
            'referenceBank' => $referenceBank,
            'workspace' => $referenceBank->getWorkspace(),
            'pathArray' => $referenceBank->getPathArray(),
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *      "/{resourceId}/create_light",
     *      requirements={"resourceId" = "\d+"},
     *      name="icap_reference_create_light"
     * )
     * @Template("ICAPReferenceBundle:Reference:newLight.html.twig")
     */
    public function createLightAction(Request $request, $resourceId)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $referenceBank = $this->getReferenceBank($resourceId);
        $this->isAllowToEdit($referenceBank);

        $form = $this->createForm($this->get('icap_reference.choose_type'));
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $type = $data['type'];

            if (!$type) {
                throw $this->createNotFoundException();
            }

            $reference = new Reference();
            $reference->setType($type);
            $reference->setTitle($this->get('translator')->trans('New reference'));
            $reference->setIconName(
                $this->get('icap_reference.form_manager')->getIcon($type)
            );
            $reference->setReferenceBank($referenceBank);

            $em->persist($reference);
            $em->flush();

            $details = array(
                'icap_reference' => array(
                    'id' => $reference->getId(),
                    'title' => $reference->getTitle(),
                    'type' => $reference->getType()
                )
            );
            $log = new LogResourceChildUpdateEvent(
                $referenceBank,
                self::REFERENCE_CHILD_TYPE,
                LogResourceChildUpdateEvent::CHILD_ACTION_CREATE,
                $details
            );
            $this->get('event_dispatcher')->dispatch('log', $log);

            return $this->redirect(
                $this->generateUrl(
                    'icap_reference_edit',
                    array(
                        'resourceId' => $referenceBank->getId(),
                        'id' => $reference->getId()
                    )
                )
            );
        }

        return array(
            'referenceBank' => $referenceBank,
            'workspace' => $referenceBank->getWorkspace(),
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *      "/{resourceId}/delete/{id}",
     *      requirements={"resourceId" = "\d+", "id" = "\d+"},
     *      name="icap_reference_delete"
     * )
     * @Template()
     */
    public function deleteAction(Request $request, $resourceId, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $referenceBank = $this->getReferenceBank($resourceId);
        $this->isAllowToEdit($referenceBank);

        $reference = $this->getReference($id);

        $form = $this->createForm(new DeleteReferenceType(), $reference);
        $form->bind($request);

        if ($request->isXMLHttpRequest()) {
            return $this->render(
                'ICAPReferenceBundle:Reference:deleteModal.html.twig',
                array(
                    'referenceBank' => $referenceBank,
                    'workspace' => $referenceBank->getWorkspace(),
                    'reference' => $reference,
                    'form' => $form->createView()
                )
            );
        }

        return array(
            'referenceBank' => $referenceBank,
            'workspace' => $referenceBank->getWorkspace(),
            'pathArray' => $referenceBank->getPathArray(),
            'reference' => $reference,
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *      "/{resourceId}/remove/{id}",
     *      requirements={"resourceId" = "\d+", "id" = "\d+"},
     *      name="icap_reference_remove"
     * )
     * @Template("ICAPReferenceBundle:Reference:delete.html.twig")
     * @Method("POST")
     */
    public function removeAction(Request $request, $resourceId, $id)
    {
        $referenceBank = $this->getReferenceBank($resourceId);
        $this->isAllowToEdit($referenceBank);

        $form = $this->createForm(new DeleteReferenceType());
        $form->bind($request);
        $reference = $this->getReference($id);

        //Check for csrf
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($reference);
            $em->flush();

            $details = array(
                'icap_reference' => array(
                    'id' => $id,
                    'title' => $reference->getTitle(),
                    'type' => $reference->getType()
                )
            );
            $log = new LogResourceChildUpdateEvent(
                $referenceBank,
                self::REFERENCE_CHILD_TYPE,
                LogResourceChildUpdateEvent::CHILD_ACTION_DELETE,
                $details
            );
            $this->get('event_dispatcher')->dispatch('log', $log);

            return $this->redirect(
                $this->generateUrl(
                    'icap_reference_list',
                    array(
                        'resourceId' => $referenceBank->getId()
                    )
                )
            );
        }

        return array(
            'referenceBank' => $referenceBank,
            'workspace' => $referenceBank->getWorkspace(),
            'pathArray' => $referenceBank->getPathArray(),
            'reference' => $reference,
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *      "/{resourceId}/new_custom_field/{id}",
     *      requirements={"resourceId" = "\d+", "id" = "\d+"},
     *      name="icap_reference_new_custom_field"
     * )
     * @Template()
     */
    public function newCustomFieldAction(Request $request, $resourceId, $id)
    {
        $referenceBank = $this->getReferenceBank($resourceId);
        $this->isAllowToEdit($referenceBank);

        $form = $this
            ->get('icap_reference.form_manager')
            ->getCustomForm(new CustomField());
        $reference = $this->getReference($id);

        return array(
            'referenceBank' => $referenceBank,
            'workspace' => $referenceBank->getWorkspace(),
            'pathArray' => $referenceBank->getPathArray(),
            'reference' => $reference,
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *      "/{resourceId}/create_custom_field/{id}",
     *      name="icap_reference_create_custom_field"
     * )
     * @Template("ICAPReferenceBundle:Reference:newCustomField.html.twig")
     */
    public function createCustomFieldAction(Request $request, $resourceId, $id)
    {
        $referenceBank = $this->getReferenceBank($resourceId);
        $this->isAllowToEdit($referenceBank);

        $reference = $this->getReference($id);

        $customField = new CustomField();
        $form = $this->get('icap_reference.form_manager')->getCustomForm($customField);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $reference->addCustomField($customField);
            $em->persist($reference);
            $em->flush();

            return $this->redirect(
                $this->generateUrl(
                    'icap_reference_edit',
                    array(
                        'resourceId' => $referenceBank->getId(),
                        'id' => $reference->getId()
                    )
                )
            );
        }

        $log = new LogResourceReadEvent($referenceBank);
        $this->get('event_dispatcher')->dispatch('log', $log);

        return array(
            'referenceBank' => $referenceBank,
            'workspace' => $referenceBank->getWorkspace(),
            'pathArray' => $referenceBank->getPathArray(),
            'id' => $id,
            'form' => $form->createView(),
            'reference' => $reference
        );
    }

    /**
     * Delete a Custom field.
     *
     * @Route(
     *      "/{resourceId}/delete_custom_field/{id}",
     *      name="icap_reference_delete_custom_field"
     * )
     */
    public function deleteCustomFieldAction(Request $request, $resourceId, $id)
    {
        $referenceBank = $this->getReferenceBank($resourceId);
        $this->isAllowToEdit($referenceBank);

        $em = $this->getDoctrine()->getManager();
        $customField = $em
            ->getRepository('ICAPReferenceBundle:CustomField')
            ->find($id);
        if (!$customField) {
            throw $this->createNotFoundException('The customField does not exist');
        }

        $referenceId = $customField->getReference()->getId();
        if (!$customField) {
            throw $this->createNotFoundException('Unable to find customField.');
        }

        $em->remove($customField);
        $em->flush();

        return $this->redirect(
            $this->generateUrl(
                'icap_reference_edit',
                array(
                    'resourceId' => $referenceBank->getId(),
                    'id' => $referenceId
                )
            )
        );
    }

    /**
     * Edit Options for referenceBanks
     *
     * @Route(
     *      "/edit_options",
     *      name="icap_reference_edit_options"
     * )
     */
    public function editReferenceBankOptionsAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();
        $referenceOptions = $this->getOptions();

        $form = $this
            ->container
            ->get('form.factory')
            ->create(new ReferenceBankOptionsType(), $referenceOptions);
        $form->bindRequest($this->get('request'));

        if ($form->isValid()) {
            $referenceOptions = $form->getData();
            $em->persist($referenceOptions);
            $em->flush();

            return new RedirectResponse(
                $this->generateUrl('claro_admin_plugins')
            );
        }

        return $this->render(
            'ICAPReferenceBundle::plugin_options_form.html.twig',
            array('form' => $form->createView())
        );
    }
    /**
     * @Route(
     *      "/{resourceId}/external_search_no_js/{id}",
     *      name="icap_reference_external_search_no_js"
     * )
     */
    public function externalSearchNoJsAction(Request $request, $resourceId, $id)
    {
        $search = $request->get('search');
        $search = urlencode($search);

        return $this->redirect(
            $this->generateUrl(
                'icap_reference_external_search',
                array(
                    'resourceId' => $resourceId,
                    'id' => $id, 'search' => $search
                )
            )
        );
    }

    /**
     * Search info on external api
     *
     * @Route(
     *      "/{resourceId}/external_search/{id}/{search}",
     *      name="icap_reference_external_search", defaults={"page" = 1}
     * )
     * @Route(
     *      "/{resourceId}/external_search/{id}/{search}/{page}",
     *      name="icap_reference_external_search_paginated",
     *      defaults={"page" = 1}
     * )
     * @Template()
     */
    public function externalSearchAction($resourceId, $id, $search, $page)
    {
        if (! $this->isOptionsSet()) {
            throw new NotFoundHttpException();
        }

        $decodedSearch = urldecode($search);

        $referenceBank = $this->getReferenceBank($resourceId);
        $this->isAllowToEdit($referenceBank);

        $reference = $this->getReference($id);

        $referencesConfiguration = $this
            ->get('icap_reference.form_manager')
            ->getReferencesConfiguration();
        $types = $referencesConfiguration['types'];
        $type = $types[$reference->getType()];
        $searchCategory = $type['amazon_search_category'];

        if ($searchCategory == null) {
            throw new NotFoundHttpException();
        }

        $searchCountry = $this->getOptions()->getAmazonCountry();
        $searchApiKey = $this->getOptions()->getAmazonApiKey();
        $searchSecretKey = $this->getOptions()->getAmazonSecretKey();
        $searchAssociateTag = $this->getOptions()->getAmazonAssociateTag();

        $client = new AmazonECS(
            $searchApiKey,
            $searchSecretKey,
            $searchCountry,
            $searchAssociateTag
        );
        $client->returnType(AmazonECS::RETURN_TYPE_ARRAY);
        $client  = $client
            ->category($searchCategory)
            ->responseGroup('Large');

        $adapter = new AmazonECSAdapter($client, $decodedSearch);
        $pager   = new PagerFanta($adapter);
        //Do not change max per page, it's an amazon constraint
        $pager->setMaxPerPage(10);
        try {
            $pager->setCurrentPage($page);
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
        }

        return array(
            'referenceBank' => $referenceBank,
            'workspace' => $referenceBank->getWorkspace(),
            'pathArray' => $referenceBank->getPathArray(),
            'reference' => $reference,
            'search' => $search,
            'decodedSearch' => $decodedSearch,
            'pager' => $pager,
            'searchCategory' => $searchCategory
        );
    }

    /**
     * Search info on external api
     *
     * @Route(
     *      "/{resourceId}/copyExternal_search/{id}",
     *      name="icap_reference_copy_external_search"
     * )
     * @Template()
     */
    public function copyExternalSearchAction(Request $request, $resourceId, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $referenceBank = $this->getReferenceBank($resourceId);
        $this->isAllowToEdit($referenceBank);

        $reference = $this->getReference($id);

        $serviceType = $this->get(
            $this->get('icap_reference.form_manager')->getServiceName($reference->getType())
        );
        $serviceType->extractData($request, $reference);
        $em->merge($reference);
        $em->flush();

        return $this->redirect(
            $this->generateUrl(
                'icap_reference_edit',
                array(
                    'resourceId' => $referenceBank->getId(),
                    'id' => $reference->getId()
                )
            )
        );
    }
}
 