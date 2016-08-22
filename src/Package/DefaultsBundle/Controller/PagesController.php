<?php

namespace Package\DefaultsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/")
 */
class PagesController extends Controller
{
    /**
     * @Route("/",
     *     name="PackageDefaultsBundle:Pages:Index"
     * )
     * @Method({"GET", "HEAD"})
     *
     * @Template
     */
    public function indexAction()
    {
        return array();
    }
}