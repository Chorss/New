<?php

namespace Pakiet\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @Route("/task")
 */
class TaskController extends Controller
{
    /**
     * @Route("/", name="pakiet_taskbundle_tasks_index")
     *
     * @Template
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/view", name="pakiet_taskBundle_view")
     *
     * @Template
     */
    public function viewAction()
    {
        return array();
    }

    /**
     * @Route("/add")
     *
     * @Template
     */
    public function addAction()
    {
        $form = $this->createFormBuilder()
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('inStock', ChoiceType::class, array(
                'choices' => array('In Stock' => true, 'Out of Stock' => false),
                // always include this
                'choices_as_values' => true,
            ))
            ->add('save', SubmitType::class, array('label' => 'Create taks'))->getForm();
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/edit")
     *
     * @Template
     */
    public function editAction()
    {
        return array();
    }

    /**
     * @Route("/remove")
     *
     * @Template
     */
    public function removeAction()
    {
        return array();
    }
}