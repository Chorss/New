<?php

namespace Package\UserBundle\Controller;

use Package\UserBundle\Form\Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/user")
 * @Security("has_role('ROLE_USER')")
 */
class UserController extends Controller
{
    /**
     * @Route(
     *     "/",
     *     name="PackageUserBundle:User:Edit"
     * )
     * @Method({"GET", "POST", "HEAD"})
     *
     * @Template
     */
    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $user = $em->getRepository('PackageUserBundle:User')->find($this->getUser()->getId());

        if (is_null($user)) {
            $this->addFlash('danger', $translator->trans('User not found'));
            return $this->redirectToRoute('PackageDefaultsBundle:Pages:Index');
        }

        $userEditForm = $this->createForm(new Type\UserType(), $user);
        $userEditForm->handleRequest($request);

        $userChangePasswordForm = $this->createForm(new Type\ChangePasswordType());
        $userChangePasswordForm->handleRequest($request);

        if ($userEditForm->isSubmitted() && $userEditForm->isValid()) {
            try {
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', $translator->trans('User modified'));
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            } finally {
                return $this->redirectToRoute('PackageUserBundle:User:Edit');
            }
        }

        if ($userChangePasswordForm->isSubmitted() && $userChangePasswordForm->isValid()) {
            try {
                $userManager = $this->get('user_manager');
                $password = $userChangePasswordForm->get('plainPassword')->getData();
                $userManager->changePassword($user, $password);
                $this->addFlash('success', $translator->trans('Changed password'));
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            } finally {
                return $this->redirectToRoute('PackageUserBundle:User:Edit');
            }
        }

        return array(
            'userEditForm' => $userEditForm->createView(),
            'userChangePasswordForm' => $userChangePasswordForm->createView()
        );
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @Route(
     *      "/change-role",
     *      name = "PackageUserBundle:User:ChangeRole"
     * )
     * @Method({"GET", "POST", "HEAD"})
     *
     * @Template
     */
    public function changeRoleAction(Request $request)
    {
        $translator = $this->get('translator');

        $form = $this->createForm(new Type\ChangeRoleType());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $userManager = $this->get('user_manager');
                $username = $form->get('username')->getData();
                $role = $form->get('role')->getData();
                $userManager->changeRole($username, $role);
                $this->addFlash('success', $translator->trans("Changed role"));
                return $this->redirectToRoute('PackageDefaultsBundle:Pages:Index');
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('PackageUserBundle:User:ChangeRole');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }
}