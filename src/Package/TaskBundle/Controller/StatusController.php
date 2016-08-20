<?php

namespace Package\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Package\TaskBundle\Entity\Status;
use Package\TaskBundle\Form\Type;

/**
 * @Route("/status")
 * @Security("has_role('ROLE_ADMIN')")
 */
class StatusController extends Controller
{
    /**
     * @Route(
     *     "/{page}",
     *     defaults={"page" = 1},
     *     requirements={"page": "\d+"},
     *     name="PackageTaskBundle:Status:Index"
     * )
     * @Method({"GET", "HEAD"})
     *
     * @Template
     */
    public function indexAction(Request $request, $page)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT a FROM PackageTaskBundle:Status a";
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
     *     name="PackageTaskBundle:Status:Add"
     * )
     * @Method({"GET", "POST", "HEAD"})
     *
     * @Template
     */
    public function addAction(Request $request)
    {
        $status = new Status();
        $translator = $this->get('translator');
        $form = $this->createForm(new Type\StatusType(), $status);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($status);
                $em->flush();
                $this->addFlash('success', $translator->trans('Added status'));
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            } finally {
                return $this->redirectToRoute('PackageTaskBundle:Status:Index');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *     "/edit/{id}",
     *     name="PackageTaskBundle:Status:Edit"
     * )
     * @Method({"GET", "POST", "HEAD"})
     *
     * @Template
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $status = $em->getRepository('PackageTaskBundle:Status')->find((int)$id);
        $translator = $this->get('translator');

        if (is_null($status)) {
            $this->addFlash('danger', $translator->trans('Status not found'));
            return $this->redirectToRoute('PackageTaskBundle:Status:Index');
        }

        $form = $this->createForm(new Type\StatusType(), $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->persist($status);
                $em->flush();
                $this->addFlash('success', $translator->trans('Status modified'));
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            } finally {
                return $this->redirectToRoute('PackageTaskBundle:Status:Index');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *     "/remove/{id}",
     *     name="PackageTaskBundle:Status:Remove"
     * )
     * @Method({"GET", "POST", "HEAD"})
     */
    public function removeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $status = $em->getRepository('PackageTaskBundle:Status')->find((int)$id);
        $translator = $this->get('translator');

        if (is_null($status)) {
            $this->addFlash('danger', $translator->trans('Status not found'));
            return $this->redirectToRoute('PackageTaskBundle:Status:Index');
        }

        try {
            $em->remove($status);
            $em->flush();
            $this->addFlash('success', $translator->trans('Status removed'));
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        } finally {
            return $this->redirectToRoute('PackageTaskBundle:Status:Index');
        }
    }
}