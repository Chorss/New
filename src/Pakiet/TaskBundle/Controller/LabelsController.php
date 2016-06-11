<?php
/**
 * Created by PhpStorm.
 * User: nikk
 * Date: 10.06.2016
 * Time: 17:55
 */

namespace Pakiet\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Pakiet\TaskBundle\Entity\Labels;
use Pakiet\TaskBundle\Form\Type;

/**
 * @Route("/labels")
 */
class LabelsController extends Controller
{
    /**
     * @Route(
     *     "/{page}",
     *     defaults={"page" = 1},
     *     requirements={"page": "\d+"},
     *     name="PakietTaskBundle:Labels:Index"
     * )
     *
     * @Template
     */
    public function indexAction($page)
    {
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM PakietTaskBundle:Labels a";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 10);

        return array(
            'pagination' => $pagination
        );
    }

    /**
     * @Route(
     *     "/add",
     *     name="PakietTaskBundle:Labels:Add"
     * )
     *
     * @Template
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $label = new Labels();

        $form = $this->createForm(new Type\AddLabelsType(), $label);

        $form->handleRequest($request);

        if($request->isMethod('POST') && $form->isValid())
        {
            try{
                $em->persist($label);
                $em->flush();
                $this->addFlash('success', 'Add labels');
                return $this->redirectToRoute('PakietTaskBundle:Labels:Index');
            }catch (Exception $e){
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('PakietTaskBundle:Labels:Index');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *     "/edit/{id}",
     *     name="PakietTaskBundle:Labels:Edit"
     * )
     *
     * @Template
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $label = $em->getRepository('PakietTaskBundle:Labels')->find( (int)$id );

        if(is_null($label)){
            $this->addFlash('danger', 'Is null label');
            return $this->redirectToRoute('PakietTaskBundle:Labels:Index');
        }

        $form = $this->createForm(new Type\AddLabelsType(), $label);
        $form->handleRequest($request);

        if($request->isMethod('POST') && $form->isValid()){
            try{
                $em->persist($label);
                $em->flush();
                $this->addFlash('success', 'edit label');
                return $this->redirectToRoute('PakietTaskBundle:Labels:Index');
            }catch (Exception $e){
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('PakietTaskBundle:Labels:Index');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *     "/remove/{id}",
     *     name="PakietTaskBundle:Labels:Remove"
     * )
     *
     * @Template
     */
    public function removeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $label = $em->getRepository('PakietTaskBundle:Labels')->find( (int)$id );

        if(is_null($label)){
            $this->addFlash('danger', 'In null label');
            return $this->redirectToRoute('PakietTaskBundle:Labels:Index');
        }

        try{
            $em->remove($label);
            $em->flush();
            $this->addFlash('success', 'Remove labels');
            return $this->redirectToRoute('PakietTaskBundle:Labels:Index');
        }catch (Exception $e){
            $this->addFlash('danger', $e->getMessage());
            return $this->redirectToRoute('PakietTaskBundle:Labels:Index');
        }
    }
}