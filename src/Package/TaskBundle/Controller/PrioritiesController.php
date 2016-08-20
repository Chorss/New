<?php

namespace Package\TaskBundle\Controller;

use Package\TaskBundle\Entity\Priorities;
use Package\TaskBundle\Form\Type\PrioritiesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/priorities")
 * @Security("has_role('ROLE_ADMIN')")
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
     * @Method({"GET", "HEAD"})
     *
     * @Template
     */
    public function indexAction($page)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT a FROM PackageTaskBundle:Priorities a";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 10);

        return array(
            'pagination' => $pagination
        );
    }

    /**
     * @Route(
     *     "/add",
     *     name="PackageTaskBundle:Priorities:Add"
     * )
     * @Method({"GET", "POST", "HEAD"})
     *
     * @Template
     */
    public function addAction(Request $request)
    {
        $translator = $this->get('translator');
        $priorities = new Priorities();

        $form = $this->createForm(new PrioritiesType(), $priorities);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($priorities);
                $em->flush();
                $this->addFlash('success', $translator->trans('Added priority'));
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            } finally {
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
     * @Method({"GET", "POST", "HEAD"})
     *
     * @Template
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $priorities = $em->getRepository('PackageTaskBundle:Priorities')->find((int)$id);

        if (is_null($id)) {
            $this->addFlash('danger', $translator->trans('Priority not found:'));
            return $this->redirectToRoute('PackageTaskBundle:Priorities:Index');
        }

        $form = $this->createForm(new PrioritiesType(), $priorities);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->persist($priorities);
                $em->flush();
                $this->addFlash('success', $translator->trans('Priority modified'));
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            } finally {
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
     * @Method({"GET", "POST", "HEAD"})
     */
    public function removeAction($id)
    {
        $translator = $this->get('translator');
        $em = $em = $this->getDoctrine()->getManager();
        $priorities = $em->getRepository('PackageTaskBundle:Priorities')->find((int)$id);

        if (is_null($priorities)) {
            $this->addFlash('danger', $translator->trans('Priority not found'));
            return $this->redirectToRoute('PackageTaskBundle:Priorities:Index');
        }

        try {
            $em->remove($priorities);
            $em->flush();
            $this->addFlash('success', $translator->trans('Priority removed'));
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        } finally {
            return $this->redirectToRoute('PackageTaskBundle:Priorities:Index');
        }
    }
}