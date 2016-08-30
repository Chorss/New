<?php

namespace Package\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Package\UserBundle\Entity\User;
use Package\UserBundle\Form\Type;

class SecurityController extends Controller
{
    /**
     * @Route("/register",
     *     name="PackageUserBundle:Security:Register"
     * )
     * @Method({"GET", "POST", "HEAD"})
     *
     * @Template
     */
    public function registerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');

        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('danger', $translator->trans('Registration is disabled'));
            return $this->redirectToRoute('PackageDefaultsBundle:Pages:Index');
        }

        $user = new User();
        $form = $this->createForm(new Type\RegisterUserType(), $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $userManager = $this->get('user_manager');
                $userManager->registerUser($user);
                $this->addFlash('success', $translator->trans('Registered user'));
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            } finally {
                return $this->redirectToRoute('PackageDefaultsBundle:Pages:Index');
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/login",
     *     name="PackageUserBundle:Security:Login"
     *     )
     * @Method({"GET", "POST", "HEAD"})
     *
     * @Template()
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $form = $this->createForm(new Type\LoginType(), array(
            'username' => $authenticationUtils->getLastUsername()
        ));

        return array(
            'form' => $form->createView(),
            'error' => $error
        );
    }

    /**
     * @Route("/remember-password",
     *     name="PackageUserBundle:Security:RememberPassword"
     * )
     * @Method({"GET", "POST", "HEAD"})
     *
     * @Template()
     */
    public function rememberPasswordAction(Request $request)
    {
        $translator = $this->get('translator');
        $form = $this->createForm(new Type\RememberPasswordType());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $userEmail = $form->get('email')->getData();
                $userManager = $this->get('user_manager');
                $userManager->sendResetPasswordLink($userEmail);
                $this->addFlash('success', $translator->trans('Check your email'));
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            } finally {
                return $this->redirectToRoute('PackageDefaultsBundle:Pages:Index');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *      "/reset-password/{actionToken}",
     *      name = "PackageUserBundle:Security:ResetPassword"
     * )
     * @Method({"GET", "HEAD"})
     */
    public function resetPasswordAction($actionToken)
    {
        try {
            $translator = $this->get('translator');
            $userManager = $this->get('user_manager');
            $userManager->resetPassword($actionToken);
            $this->addFlash('success', $translator->trans('The new password was sent'));
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        } finally {
            return $this->redirectToRoute('PackageDefaultsBundle:Pages:Index');
        }
    }

    /**
     * @Route(
     *      "/account-activation/{actionToken}",
     *      name = "PackageUserBundle:Security:ActivateAccount"
     * )
     * @Method({"GET", "HEAD"})
     */
    public function activateAccountAction($actionToken)
    {
        try {
            $translator = $this->get('translator');
            $userManager = $this->get('user_manager');
            $userManager->activateAccount($actionToken);
            $this->addFlash('success', $translator->trans('Your account was activated'));
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        } finally {
            return $this->redirectToRoute('PackageDefaultsBundle:Pages:Index');
        }
    }
}