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
            ->add('status', EntityType::class, array(
                    'class' => 'PackageTaskBundle:Status',
                    'choice_label' => 'Name',
                    'expanded' => true,
                    'multiple' => false,
                    'required' => true,
                    'placeholder' => 'Choose something'
                )
            )
            ->add('priorities', EntityType::class, array(
                    'class' => 'PackageTaskBundle:Priorities',
                    'choice_label' => 'Name',
                    'expanded' => true,
                    'multiple' => false,
                    'required' => true,
                    'placeholder' => 'Choose something',
                )
            )
            ->add('labels', EntityType::class, array(
                    'class' => 'PackageTaskBundle:Labels',
                    'choice_label' => 'Name',
                    'expanded' => true,
                    'multiple' => false,
                    'required' => false,
                    'placeholder' => 'Choose something'
                )
            )
            ->add('submit', SubmitType::class, array('label' => 'Submit'));
    }
}