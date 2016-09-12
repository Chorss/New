<?php

namespace Package\TaskBundle\Controller;

use Package\TaskBundle\Entity\Label;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Package\TaskBundle\Form\Type\LabelsType;

/**
 * @Route("/labels")
 * @Security("has_role('ROLE_USER')")
 */
class LabelController extends Controller
{
    /**
     * @Route(
     *     "/{page}",
     *     defaults={"page" = 1},
     *     requirements={"page": "\d+"},
     *     name="PackageTaskBundle:Label:Index"
     * )
     * @Method({"GET", "HEAD"})
     *
     * @Template
     */
    public function indexAction($page)
    {
        $query = $this->getDoctrine()->getRepository("PackageTaskBundle:Label")->getQueryPagination();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 10);

        return array(
            'pagination' => $pagination
        );
    }

    /**
     * @Route(
     *     "/add",
     *     name="PackageTaskBundle:Label:Add"
     * )
     * @Method({"GET", "POST", "HEAD"})
     *
     * @Template
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $label = new Label();

        $form = $this->createForm(new LabelsType(), $label);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->persist($label);
                $em->flush();
                $this->addFlash('success', $translator->trans('Added label'));
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            } finally {
                return $this->redirectToRoute('PackageTaskBundle:Label:Index');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *     "/edit/{id}",
     *     name="PackageTaskBundle:Label:Edit"
     * )
     * @Method({"GET", "POST", "HEAD"})
     *
     * @Template
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $label = $em->getRepository('PackageTaskBundle:Label')->find((int)$id);

        if (is_null($label)) {
            $this->addFlash('danger', $translator->trans('Label not found'));
            return $this->redirectToRoute('PackageTaskBundle:Label:Index');
        }

        $form = $this->createForm(new LabelsType(), $label);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->persist($label);
                $em->flush();
                $this->addFlash('success', $translator->trans('Label modified'));
                return $this->redirectToRoute('PackageTaskBundle:Label:Edit', array('id' => (int)$id));
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('PackageTaskBundle:Label:Index');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *     "/remove/{id}",
     *     name="PackageTaskBundle:Label:Remove"
     * )
     * @Method({"GET", "POST", "HEAD"})
     *
     * @Template
     */
    public function removeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $label = $em->getRepository('PackageTaskBundle:Label')->find((int)$id);

        if (is_null($label)) {
            $this->addFlash('danger', $translator->trans('Label not found'));
            return $this->redirectToRoute('PackageTaskBundle:Label:Index');
        }

        try {
            $em->remove($label);
            $em->flush();
            $this->addFlash('success', $translator->trans('Label removed'));
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        } finally {
            return $this->redirectToRoute('PackageTaskBundle:Label:Index');
        }
    }
}