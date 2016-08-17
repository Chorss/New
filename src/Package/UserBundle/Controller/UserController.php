<?php

namespace Package\UserBundle\Controller;

use Package\UserBundle\Entity\User;
use Package\UserBundle\Form\Type\UserType;
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
     *     name="PackageUserBundle:User:View"
     * )
     * @Method({"GET", "HEAD"})
     *
     * @Template
     */
    public function viewAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        var_dump($this->getUser()->getId());
        $user = $em->getRepository('PackageUserBundle:User')->find( $this->getUser()->getId() );

        if(is_null($user)){
            $this->addFlash('danger', $translator->trans('User not found'));
            return $this->redirectToRoute('PackageDefaultsBundle:Pages:Index');
        }

        $form = $this->createForm(new UserType(), $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            try{
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', $translator->trans('User modified'));
            }catch (\Exception $e){
                $this->addFlash('danger', $e->getMessage());
            }finally{
                return $this->redirectToRoute('PackageUserBundle:User:View');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     *
     */
    public function editUser()
    {

    }
}