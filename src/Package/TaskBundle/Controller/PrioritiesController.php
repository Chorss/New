<?php

namespace Package\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Pakiet\TaskBundle\Entity\Priorities;
use Pakiet\TaskBundle\Form\Type;

/**
 * @Route("/priorities")
 */
class PrioritiesController extends Controller
{
    /**
     * @Route(
     *     "/{page}",
     *     defaults={"page" = 1},
     *     requirements={"page": "\d+"},
     *     name="PackageTaskBundle:Priorities:Index"
     * )
     *
     * @Template
     */
    public function indexAction($page)
    {
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM PackageTaskBundle:Priorities a";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 10);

        return array(
            'pagination' => $pagination
        );
    }
    /**
     * @Route(
     *     "/view/{id}",
     *     name="PackageTaskBundle:Priorities:View"
     * )
     *
     * @Template
     */
    public function viewAction($id)
    {
        $priorities = $this->getDoctrine()->getRepository('PackageTaskBundle:Priorities')->find( (int)$id );

        if(is_null($priorities)){
            $this->addFlash('danger', 'Is null priorities');
            return $this->redirectToRoute('PackageTaskBundle:Priorities:Index');
        }

        return array(
            'priorities' => $priorities
        );
    }

    /**
     * @Route(
     *     "/add",
     *     name="PackageTaskBundle:Priorities:Add"
     * )
     *
     * @Template
     */
    public function addAction(Request $request)
    {
        $priorities = new Priorities();
        $form = $this->createForm(new Type\AddPrioritiesType(), $priorities);

        $form->handleRequest($request);

        if($request->isMethod('POST') && $form->isValid())
        {
            try{
                $em = $this->getDoctrine()->getManager();
                $em->persist($priorities);
                $em->flush();
                $this->addFlash('success', 'add priorities');
                return $this->redirectToRoute('PackageTaskBundle:Priorities:Index');
            }catch (Exception $e){
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('PackageTaskBundle:Priorities:Index');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *     "/edit/{id}",
     *     name="PackageTaskBundle:Priorities:Edit"
     * )
     *
     * @Template
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $priorities = $em->getRepository('PackageTaskBundle:Priorities')->find( (int)$id );

        if(is_null($id)){
            $this->addFlash('danger', 'Is null priorities');
            return $this->redirectToRoute('PackageTaskBundle:Priorities:Index');
        }

        $form = $this->createForm(new Type\AddPrioritiesType(), $priorities);
        $form->handleRequest($request);

        if($request->isMethod('POST') && $form->isValid()){
            try{
                $em->persist($priorities);
                $em->flush();
                $this->addFlash('success', 'edit priorities');
                return $this->redirectToRoute('PackageTaskBundle:Priorities:Index');
            }catch (Exception $e){
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('PackageTaskBundle:Priorities:Index');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *     "/remove/{id}",
     *     name="PackageTaskBundle:Priorities:Remove"
     * )
     */
    public function removeAction($id)
    {
        $em = $em = $this->getDoctrine()->getManager();
        $priorities = $em->getRepository('PackageTaskBundle:Priorities')->find( (int)$id );

        if(is_null($priorities)){
            $this->addFlash('danger', 'Is null priorities');
            return $this->redirectToRoute('PackageTaskBundle:Priorities:Index');
        }

        try{
            $em->remove($priorities);
            $em->flush();
            $this->addFlash('success', 'Remove priorities');
            return $this->redirectToRoute('PackageTaskBundle:Priorities:Index');
        }catch (Exception $e){
            $this->addFlash('danger', $e->getMessage());
            return $this->redirectToRoute('PackageTaskBundle:Priorities:Index');
        }
    }
}