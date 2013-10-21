<?php

namespace Icap\ReferenceBundle\Listener;

use Claroline\CoreBundle\Event\PluginOptionsEvent;
use Claroline\CoreBundle\Event\CreateFormResourceEvent;
use Claroline\CoreBundle\Event\CreateResourceEvent;
use Claroline\CoreBundle\Event\DeleteResourceEvent;
use Claroline\CoreBundle\Event\OpenResourceEvent;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Icap\ReferenceBundle\Form\ReferenceBankType;
use Icap\ReferenceBundle\Form\ReferenceBankOptionsType;
use Icap\ReferenceBundle\Entity\ReferenceBank;
use Icap\ReferenceBundle\Entity\ReferenceBankOptions;


class ReferenceBankListener extends ContainerAware
{
    public function onCreateForm(CreateFormResourceEvent $event)
    {
        $form = $this->container->get('form.factory')->create(new ReferenceBankType(), new ReferenceBank());
        $content = $this->container->get('templating')->render(
            'ClarolineCoreBundle:Resource:createForm.html.twig',
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
        $form->handleRequest($request);

        if ($form->isValid()) {
            $dropzone = $form->getData();
            $event->setResources(array($dropzone));
        } else {
            $content = $this->container->get('templating')->render(
                'ClarolineCoreBundle:Resource:createForm.html.twig',
                array(
                    'form' => $form->createView(),
                    'resourceType' => 'icap_referencebank'
                )
            );
            $event->setErrorFormContent($content);
        }
        $event->stopPropagation();
    }

    public function onDelete(DeleteResourceEvent $event) {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->remove($event->getResource());
        $event->stopPropagation();
    }


    public function onOpen(OpenResourceEvent $event) {
        $route = $this->container
            ->get('router')
            ->generate(
                'icap_reference_list',
                array('resourceId' => $event->getResource()->getId())
            )
        ;
        $event->setResponse(new RedirectResponse($route));
        $event->stopPropagation();
    }

    public function onAdministrate(PluginOptionsEvent $event)
    {
        $referenceOptionsList = $this->container
            ->get('doctrine.orm.entity_manager')
            ->getRepository('IcapReferenceBundle:ReferenceBankOptions')
            ->findAll()
        ;

        $referenceOptions = null;
        if ((count($referenceOptionsList)) > 0) { 
            $referenceOptions = $referenceOptionsList[0]; 
        } else {
            $referenceOptions = new ReferenceBankOptions();
        }

        $form = $this->container->get('form.factory')->create(new ReferenceBankOptionsType(), $referenceOptions);
        $content = $this->container->get('templating')->render(
            'IcapReferenceBundle::plugin_options_form.html.twig', array(
                'form' => $form->createView()
            )
        );
        $response = new Response($content);
        $event->setResponse($response);
        $event->stopPropagation();
    }
}