<?php

namespace Package\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     *
     * @Template
     */
    public function registerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = new User();
        $form = $this->createForm(new Type\RegisterUserType(), $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try{
                $userManager = $this->get('user_manager');
                $userManager->registerUser($user);
                $this->addFlash('success', 'Add user');
                return $this->redirectToRoute('PackageDefaultsBundle:Pages:Index');
            }catch (\Exception $e){
                $this->addFlash('danger', $e->getMessage());
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
     *
     *
     * @Template()
     */
    public function rememberPasswordAction(Request $request)
    {
        $form = $this->createForm(new Type\RememberPasswordType());

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            try{
                $userEmail = $form->get('email')->getData();
                $userManager = $this->get('user_manager');
                $userManager->sendResetPasswordLink($userEmail);
                $this->addFlash('success', 'Udało się przypomnieć hasło');
                return $this->redirectToRoute('PackageDefaultsBundle:Pages:Index');
            }catch (\Exception $e){
                $this->addFlash('danger', $e->getMessage());
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
     */
    public function resetPasswordAction($actionToken)
    {
        try {
            $userManager = $this->get('user_manager');
            $userManager->resetPassword($actionToken);
            $this->addFlash('success', 'Na Twój adres e-mail zostało wysłane nowe hasło!');
            return $this->redirectToRoute('PackageDefaultsBundle:Pages:Index');
        }catch (\Exception $e){
            $this->addFlash('danger', $e->getMessage());
            return $this->redirectToRoute('PackageDefaultsBundle:Pages:Index');
        }
    }

    /**
     * @Route(
     *     "/change-password",
     *     name = "PackageUserBundle:Security:ChangePassword"
     * )
     *  
     * Template
     */
    public function changePasswordAction()
    {
        $em = $this->getDoctrine()->getManager();
//        if(){
//@todo
//        }
        try{
            $userManager = $this->get('user_manager');
            $userManager->changePassword($user);
            $this->addFlash('success', 'Zmieniłeś hasło');
            return $this->redirectToRoute('PackageDefaultsBundle:Pages:Index');
        }catch (\Exception $e){
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('PackageDefaultsBundle:Pages:Index');
        }
    }
    
    /**
     * @Route(
     *      "/account-activation/{actionToken}",
     *      name = "PackageUserBundle:Security:ActivateAccount"
     * )
     */
    public function activateAccountAction($actionToken)
    {
        try {
            $userManager = $this->get('user_manager');
            $userManager->activateAccount($actionToken);
            $this->get('session')->getFlashBag()->add('success', 'Twoje konto zostało aktywowane!');
            return $this->redirectToRoute('PackageDefaultsBundle:Pages:Index');
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
            return $this->redirectToRoute('PackageDefaultsBundle:Pages:Index');
        }
    }
}