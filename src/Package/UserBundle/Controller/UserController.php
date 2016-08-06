<?php

namespace Package\UserBundle\Controller;

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
    public function viewAction()
    {

    }

    /**
     *
     */
    public function editUser()
    {

    }
}