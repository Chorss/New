<?php

namespace Package\TaskBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextareaType::class, array(
                'label' => 'Name'
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Description'
            ))
            ->add('project', EntityType::class, array(
                    'class' => 'PackageTaskBundle:Project',
                    'choice_label' => 'name',
                    'required' => true
                )
            )
            ->add('assignee', EntityType::class, array(
                    'class' => 'PackageUserBundle:User',
                    'choice_label' => 'Username',
                    'required' => false
                )
            )
            ->add('status', EntityType::class, array(
                    'class' => 'PackageTaskBundle:Status',
                    'choice_label' => 'Name',
                    'required' => true
                )
            )
            ->add('priority', EntityType::class, array(
                    'class' => 'PackageTaskBundle:Priority',
                    'choice_label' => 'Name',
                    'required' => true,
                )
            )
            ->add('submit', SubmitType::class, array('label' => 'Submit'));
    }
}