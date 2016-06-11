<?php

namespace Pakiet\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Pakiet\TaskBundle\Entity\Tasks;
use Pakiet\TaskBundle\Form\Type;

/**
 * @Route("/task")
 */
class TaskController extends Controller
{
    /**
     * @Route(
     *     "/{page}",
     *     defaults={"page" = 1},
     *     requirements={"page": "\d+"},
     *     name="PakietTaskBundle:Task:Index"
     * )
     *
     * @Template
     */
    public function indexAction($page)
    {
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM PakietTaskBundle:Tasks a";
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
     *     name="PakietTaskBundle:Task:View"
     * )
     *
     * @Template
     */
    public function viewAction($id)
    {
        $task = $this->getDoctrine()->getRepository('PakietTaskBundle:Tasks')->find( (int)$id );

        if(is_null($task)){
            $this->addFlash('danger', 'Is null task');
            return $this->redirectToRoute('PakietTaskBundle:Task:Index');
        }

        return array(
            'task' => $task
        );
    }

    /**
     * @Route(
     *     "/add",
     *     name="PakietTaskBundle:Task:Add"
     * )
     *
     * @Template
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tasks = new Tasks();
        $tasks->setDateCreated(new \DateTime());
        $form = $this->createForm(new Type\AddTaskType(), $tasks);

        $form->handleRequest($request);

        if($request->isMethod('POST') && $form->isValid())
        {
            try{
                $em->persist($tasks);
                $em->flush();
                $this->addFlash('success', 'Add task');
                return $this->redirectToRoute('PakietTaskBundle:Task:Index');
            }catch (Exception $e){
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('PakietTaskBundle:Task:Index');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *     "/edit/{id}",
     *     name="PakietTaskBundle:Task:Edit"
     * )
     *
     * @Template
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('PakietTaskBundle:Tasks')->find((int)$id);

        if(is_null($task)){
            $this->addFlash('danger', 'Is null task');
            return $this->redirectToRoute('PakietTaskBundle:Task:Index');
        }

        $form = $this->createForm(new Type\AddTaskType(), $task);
        $form->handleRequest($request);

        if($request->isMethod('POST') && $form->isValid()){
            try{
                $em->persist($task);
                $em->flush();
                $this->addFlash('success','edit task');
                return $this->redirectToRoute('PakietTaskBundle:Task:View', array( 'id'=>$task->getId() ));
            }catch (Exception $e){
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('PakietTaskBundle:Task:Index');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *     "/remove/{id}",
     *     name="PakietTaskBundle:Task:Remove"
     * )
     */
    public function removeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('PakietTaskBundle:Tasks');
        $task = $repo->find($id);

        if(is_null($task)){
            $this->addFlash('danger', 'In null task');
            return $this->redirectToRoute('PakietTaskBundle:Task:Index');
        }

        try{
            $em->remove($task);
            $em->flush();
            $this->addFlash('success', 'Remove task');
            return $this->redirectToRoute('PakietTaskBundle:Task:Index');
        }catch (Exception $e){
            $this->addFlash('danger', $e->getMessage());
            return $this->redirectToRoute('PakietTaskBundle:Task:Index');
        }
    }
}