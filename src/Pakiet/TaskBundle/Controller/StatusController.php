<?php

namespace Pakiet\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Pakiet\TaskBundle\Entity\Status;
use Pakiet\TaskBundle\Form\Type;

/**
 * @Route("/status")
 */
class StatusController extends Controller
{
    /**
     * @Route(
     *     "/{page}",
     *     defaults={"page" = 1},
     *     requirements={"page": "\d+"},
     *     name="PakietTaskBundle:Status:Index"
     * )
     *
     * @Template
     */
    public function indexAction(Request $request, $page)
    {
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM PakietTaskBundle:Status a";
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
     *     name="PakietTaskBundle:Status:View"
     * )
     *
     * @Template
     */
    public function viewAction($id)
    {
        $status = $this->getDoctrine()->getRepository('PakietTaskBundle:Status')->find( (int)$id );

        if(is_null($status)){
            $this->addFlash('danger', 'Is null status');
            return $this->redirectToRoute('PakietTaskBundle:Status:Index');
        }
        
        return array(
            'status' => $status
        );
    }

    /**
     * @Route(
     *     "/add",
     *     name="PakietTaskBundle:Status:Add"
     * )
     *
     * @Template
     */
    public function addAction(Request $request)
    {
        $status = new Status();
        $form = $this->createForm(new Type\AddStatusType(), $status);

        $form->handleRequest($request);

        if($request->isMethod('POST') && $form->isValid())
        {
            try{
                $em = $this->getDoctrine()->getManager();
                $em->persist($status);
                $em->flush();
                $this->addFlash('success', 'Add status');
                return $this->redirectToRoute('PakietTaskBundle:Status:Index');
            }catch (Exception $e){
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('PakietTaskBundle:Status:Index');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *     "/edit/{id}",
     *     name="PakietTaskBundle:Status:Edit"
     * )
     *
     * @Template
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $status = $em->getRepository('PakietTaskBundle:Status')->find( (int)$id );

        if(is_null($status)){
            $this->addFlash('danger', 'Is null status');
            return $this->redirectToRoute('PakietTaskBundle:Status:Index');
        }

        $form = $this->createForm(new Type\AddStatusType(), $status);
        $form->handleRequest($request);

        if($request->isMethod('POST') && $form->isValid()){
            try{
                $em->persist($status);
                $em->flush();
                $this->addFlash('success', 'edit status');
                return $this->redirectToRoute('PakietTaskBundle:Status:Index');
            }catch (Exception $e){
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('PakietTaskBundle:Status:Index');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *     "/remove/{id}",
     *     name="PakietTaskBundle:Status:Remove"
     * )
     */
    public function removeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('PakietTaskBundle:Status');
        $status = $repo->find($id);

        if(is_null($status)){
            $this->addFlash('danger', 'Is null status');
            return $this->redirectToRoute('PakietTaskBundle:Status:Index');
        }

        try{
            $em->remove($status);
            $em->flush();
            $this->addFlash('success', 'Remove status');
            return $this->redirectToRoute('PakietTaskBundle:Status:Index');
        }catch (Exception $e){
            $this->addFlash('danger', $e->getMessage());
            return $this->redirectToRoute('PakietTaskBundle:Status:Index');
        }
    }
}