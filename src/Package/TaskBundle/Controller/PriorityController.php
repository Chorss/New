<?php

namespace Package\TaskBundle\Controller;

use Package\TaskBundle\Entity\Priority;
use Package\TaskBundle\Form\Type\PrioritiesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/priorities")
 * @Security("has_role('ROLE_USER')")
 */
class PriorityController extends Controller
{
    /**
     * @Route(
     *     "/{page}",
     *     defaults={"page" = 1},
     *     requirements={"page": "\d+"},
     *     name="PackageTaskBundle:Priority:Index"
     * )
     * @Method({"GET", "HEAD"})
     *
     * @Template
     */
    public function indexAction($page)
    {
        $query = $this->getDoctrine()->getRepository("PackageTaskBundle:Priority")->getQueryPagination();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 10);

        return array(
            'pagination' => $pagination
        );
    }

    /**
     * @Route(
     *     "/add",
     *     name="PackageTaskBundle:Priority:Add"
     * )
     * @Method({"GET", "POST", "HEAD"})
     *
     * @Template
     */
    public function addAction(Request $request)
    {
        $translator = $this->get('translator');
        $priority = new Priority();

        $form = $this->createForm(PrioritiesType::class, $priority);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($priority);
                $em->flush();
                $this->addFlash('success', $translator->trans('Added priority'));
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            } finally {
                return $this->redirectToRoute('PackageTaskBundle:Priority:Index');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *     "/edit/{id}",
     *     name="PackageTaskBundle:Priority:Edit"
     * )
     * @Method({"GET", "POST", "HEAD"})
     *
     * @Template
     */
    public function editAction(Request $request, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $priority = $em->getRepository('PackageTaskBundle:Priority')->find((int)$id);

        if (is_null($id)) {
            $this->addFlash('danger', $translator->trans('Priority not found'));
            return $this->redirectToRoute('PackageTaskBundle:Priority:Index');
        }

        $form = $this->createForm(PrioritiesType::class, $priority);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->persist($priority);
                $em->flush();
                $this->addFlash('success', $translator->trans('Priority modified'));
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            } finally {
                return $this->redirectToRoute('PackageTaskBundle:Priority:Index');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *     "/remove/{id}",
     *     name="PackageTaskBundle:Priority:Remove"
     * )
     * @Method({"GET", "POST", "HEAD"})
     */
    public function removeAction(int $id)
    {
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $priority = $em->getRepository('PackageTaskBundle:Priority')->find($id);

        if (is_null($priority)) {
            $this->addFlash('danger', $translator->trans('Priority not found'));
            return $this->redirectToRoute('PackageTaskBundle:Priority:Index');
        }

        try {
            $em->remove($priority);
            $em->flush();
            $this->addFlash('success', $translator->trans('Priority removed'));
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        } finally {
            return $this->redirectToRoute('PackageTaskBundle:Priority:Index');
        }
    }
}