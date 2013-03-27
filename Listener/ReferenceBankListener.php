<?php

namespace ICAP\ReferenceBundle\Listener;

use Claroline\CoreBundle\Library\Event\PluginOptionsEvent;
use Claroline\CoreBundle\Library\Event\CreateFormResourceEvent;
use Claroline\CoreBundle\Library\Event\CreateResourceEvent;
use Claroline\CoreBundle\Library\Event\DeleteResourceEvent;
use Claroline\CoreBundle\Library\Event\OpenResourceEvent;
use Claroline\CoreBundle\Library\Event\CopyResourceEvent;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use ICAP\ReferenceBundle\Form\ReferenceBankType;
use ICAP\ReferenceBundle\Form\ReferenceBankOptionsType;
use ICAP\ReferenceBundle\Entity\ReferenceBank;
use ICAP\ReferenceBundle\Entity\Reference;
use ICAP\ReferenceBundle\Entity\CustomField;
use ICAP\ReferenceBundle\Entity\ReferenceBankOptions;


class ReferenceBankListener extends ContainerAware
{
    public function onCreateForm(CreateFormResourceEvent $event)
    {
        $form = $this->container->get('form.factory')->create(new ReferenceBankType(), new ReferenceBank());
        $content = $this->container->get('templating')->render(
            'ClarolineCoreBundle:Resource:create_form.html.twig',
            array(
                'form' => $form->createView(),
                'resourceType' => 'icap_referencebank'
            )
        );

        $event->setResponseContent($content);
        $event->stopPropagation();
    }

    public function onCreate(CreateResourceEvent $event)
    {
        $request = $this->container->get('request');
        $form = $this->container->get('form.factory')->create(new ReferenceBankType(), new ReferenceBank());
        $form->bindRequest($request);

        if ($form->isValid()) {
            $referenceBank = $form->getData();
            $event->setResource($referenceBank);
        } else {
            $content = $this->container->get('templating')->render(
                'ClarolineCoreBundle:Resource:create_form.html.twig',
                array(
                    'form' => $form->createView(),
                    'resourceType' => 'icap_referencebank'
                )
            );
            $event->setErrorFormContent($content);
        }
        $event->stopPropagation();
    }

    public function onDelete(DeleteResourceEvent $event)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->remove($event->getResource());
        $event->stopPropagation();
    }


    public function onOpen(OpenResourceEvent $event)
    {
        $route = $this->container
            ->get('router')
            ->generate(
                'icap_reference_list',
                array('resourceId' => $event->getResource()->getId())
            );
        $event->setResponse(new RedirectResponse($route));
        $event->stopPropagation();
    }

    public function onAdministrate(PluginOptionsEvent $event)
    {
        $referenceOptionsList = $this->container
            ->get('doctrine.orm.entity_manager')
            ->getRepository('ICAPReferenceBundle:ReferenceBankOptions')
            ->findAll();

        $referenceOptions = null;
        if ((count($referenceOptionsList)) > 0) {
            $referenceOptions = $referenceOptionsList[0];
        } else {
            $referenceOptions = new ReferenceBankOptions();
        }

        $form = $this->container->get('form.factory')->create(new ReferenceBankOptionsType(), $referenceOptions);
        $content = $this->container->get('templating')->render(
            'ICAPReferenceBundle::plugin_options_form.html.twig', array(
                'form' => $form->createView()
            )
        );
        $response = new Response($content);
        $event->setResponse($response);
        $event->stopPropagation();
    }

    public function onCopy(CopyResourceEvent $event)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $resource = $event->getResource();
        $newReferenceBank = new ReferenceBank();
        $newReferenceBank->setName($resource->getName());
        $oldReferences = $resource->getReferences();

        foreach ($oldReferences as $oldReference) {
            $newReference = new Reference();
            $newReference->setTitle($oldReference->getTitle());
            $newReference->setImageUrl($oldReference->getImageUrl());
            $newReference->setDescription($oldReference->getDescription());
            $newReference->setType($oldReference->getType());
            $newReference->setUrl($oldReference->getUrl());
            $newReference->setIconName($oldReference->getIconName());
            $newReference->setData($oldReference->getData());

            $newReferenceBank->addReference($newReference);

            $oldCustomFields = $oldReference->getCustomFields();
            foreach ($oldCustomFields as $oldCustomField) {
                $newCustomField = new CustomField();
                $newCustomField->setFieldKey($oldCustomField->getFieldKey());
                $newCustomField->setFieldValue($oldCustomField->getFieldValue());

                $newReference->addCustomField($newCustomField);
            }
        }
        $em->persist($newReferenceBank);

        $event->setCopy($newReferenceBank);
        $event->stopPropagation();
    }
}