<?php

namespace Package\TaskBundle\Controller;

use Doctrine\Common\Collections\Collection;
use Package\TaskBundle\Entity\Task;
use Package\TaskBundle\Form\Type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
    public function indexAction(int $page)
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
     *     "/view/{taskId}/{page}",
     *     name="PackageTaskBundle:Task:View",
     *     requirements={"taskId": "\d+", "page": "\d+"},
     *     defaults={"page" = 1}
     * )
     * @Method({"GET", "HEAD"})
     *
     * @Template
     */
    public function viewAction(Request $request, int $taskId, int $page)
    {
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');

        $task = $em->getRepository('PackageTaskBundle:Task')->find($taskId);

        if (is_null($task)) {
            $this->addFlash('danger', $translator->trans('Task not found'));
            return $this->redirectToRoute('PackageTaskBundle:Task:Index');
        }

        $query = $em->getRepository('PackageTaskBundle:Comment')->getQueryPagination($taskId);
        $pagination = $this->get('knp_paginator');
        $commentPagination = $pagination->paginate($query, $page, 10);

        return array(
            'task' => $task,
            'sumWorklog' => $this->getSumWorklog($task->getWorklog()),
            'comments' => $commentPagination
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

        $tasks = new Task();

        $form = $this->createForm(Type\TaskType::class, $tasks);
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
    public function editAction(Request $request, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('PackageTaskBundle:Task')->find($id);
        $translator = $this->get('translator');

        if (is_null($task)) {
            $this->addFlash('danger', $translator->trans('Task not found'));
            return $this->redirectToRoute('PackageTaskBundle:Task:Index');
        }

        $form = $this->createForm(Type\TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $task->setUpdated(new \DateTime());
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
    public function removeAction(int $id)
    {
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('PackageTaskBundle:Task')->find($id);

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

    /**
     * getSumWorklog
     *
     * @param Collection $worklogs
     * @return int sum
     */
    private function getSumWorklog(Collection $worklogs)
    {
        $sum = 0;
        foreach ($worklogs as $worklog) {
            $sum += $worklog->getTimeSpent();
        }

        return $sum;
    }
}