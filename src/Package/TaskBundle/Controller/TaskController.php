<?php

namespace Package\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Package\TaskBundle\Entity\Tasks;
use Package\TaskBundle\Form\Type;

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
     *     name="PackageTaskBundle:Task:Index"
     * )
     *
     * @Template
     */
    public function indexAction($page)
    {
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM PackageTaskBundle:Tasks a";
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
     *     name="PackageTaskBundle:Task:View"
     * )
     *
     * @Template
     */
    public function viewAction($id)
    {
        $task = $this->getDoctrine()->getRepository('PackageTaskBundle:Tasks')->find( (int)$id );
        $trans = $this->get('translator');

        if(is_null($task)){
            $this->addFlash('danger', $trans->trans('Is null task'));
            return $this->redirectToRoute('PackageTaskBundle:Task:Index');
        }

        return array(
            'task' => $task
        );
    }

    /**
     * @Route(
     *     "/add",
     *     name="PackageTaskBundle:Task:Add"
     * )
     *
     * @Template
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tasks = new Tasks;
        $tasks->setDateCreated(new \DateTime());
        $form = $this->createForm(new Type\AddTaskType(), $tasks);

        $form->handleRequest($request);

        if($request->isMethod('POST') && $form->isValid())
        {
            try{
                $em->persist($tasks);
                $em->flush();
                $this->addFlash('success', 'Add task');
                return $this->redirectToRoute('PackageTaskBundle:Task:Index');
            }catch (\Exception $e){
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('PackageTaskBundle:Task:Index');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *     "/edit/{id}",
     *     name="PackageTaskBundle:Task:Edit"
     * )
     *
     * @Template
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('PackageTaskBundle:Tasks')->find((int)$id);

        if(is_null($task)){
            $this->addFlash('danger', 'Is null task');
            return $this->redirectToRoute('PackageTaskBundle:Task:Index');
        }

        $form = $this->createForm(new Type\AddTaskType(), $task);
        $form->handleRequest($request);

        if($request->isMethod('POST') && $form->isValid()){
            try{
                $em->persist($task);
                $em->flush();
                $this->addFlash('success','edit task');
                return $this->redirectToRoute('PackageTaskBundle:Task:View', array( 'id'=>$task->getId() ));
            }catch (Exception $e){
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('PackageTaskBundle:Task:Index');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *     "/remove/{id}",
     *     name="PackageTaskBundle:Task:Remove"
     * )
     */
    public function removeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('PackageTaskBundle:Tasks');
        $task = $repo->find($id);

        if(is_null($task)){
            $this->addFlash('danger', 'In null task');
            return $this->redirectToRoute('PackageTaskBundle:Task:Index');
        }

        try{
            $em->remove($task);
            $em->flush();
            $this->addFlash('success', 'Remove task');
            return $this->redirectToRoute('PackageTaskBundle:Task:Index');
        }catch (Exception $e){
            $this->addFlash('danger', $e->getMessage());
            return $this->redirectToRoute('PackageTaskBundle:Task:Index');
        }
    }
}