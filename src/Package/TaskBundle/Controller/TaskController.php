<?php

namespace Package\TaskBundle\Controller;

use Package\TaskBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Package\TaskBundle\Form\Type;

/**
 * @Route("/task")
 * @Security("has_role('ROLE_USER')")
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
     * @Method({"GET", "HEAD"})
     *
     * @Template
     */
    public function indexAction($page)
    {
        $query = $this->getDoctrine()->getRepository('PackageTaskBundle:Task')->getQueryPagination();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 10);

        return array(
            'pagination' => $pagination
        );
    }

    /**
     * @Route(
     *     "/add",
     *     name="PackageTaskBundle:Task:Add"
     * )
     * @Method({"GET", "POST", "HEAD"})
     *
     * @Template
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');

        $user = $this->getUser();
        $tasks = new Task();
        $tasks->setDateCreated(new \DateTime());
        $tasks->setAuthor(
            $em->getRepository('PackageUserBundle:User')->findOneBy(array('id' => $user->getId()))
        );
        $form = $this->createForm(new Type\TaskType(), $tasks);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->persist($tasks);
                $em->flush();
                $this->addFlash('success', $translator->trans('Added task'));
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            } finally {
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
     * @Method({"GET", "POST", "HEAD"})
     *
     * @Template
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('PackageTaskBundle:Task')->find((int)$id);
        $translator = $this->get('translator');

        if (is_null($task)) {
            $this->addFlash('danger', $translator->trans('Task not found'));
            return $this->redirectToRoute('PackageTaskBundle:Task:Index');
        }

        $form = $this->createForm(new Type\TaskType(), $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->persist($task);
                $em->flush();
                $this->addFlash('success', $translator->trans('Task modified'));
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            } finally {
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
     * @Method({"GET", "POST", "HEAD"})
     */
    public function removeAction($id)
    {
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('PackageTaskBundle:Task')->find((int)$id);

        if (is_null($task)) {
            $this->addFlash('danger', $translator->trans('Task not found'));
            return $this->redirectToRoute('PackageTaskBundle:Task:Index');
        }

        try {
            $em->remove($task);
            $em->flush();
            $this->addFlash('success', $translator->trans('Task removed'));
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        } finally {
            return $this->redirectToRoute('PackageTaskBundle:Task:Index');
        }
    }
}