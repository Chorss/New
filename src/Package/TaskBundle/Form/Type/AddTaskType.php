<?php

namespace Package\TaskBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AddTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextareaType::class, array(
                'label' => 'Nazwa'
            ))
            ->add('status', EntityType::class, array(
                    'class' => 'PackageTaskBundle:Status',
                    'choice_label' => 'name',
                    'expanded' => true,
                    'multiple' => false,
                    'required' => true,
                    'placeholder' => '-- Wybierz coś --',
                    'data_class' => null,
                )
            )
            ->add('priorities', EntityType::class, array(
                    'class' => 'PackageTaskBundle:Priorities',
                    'choice_label' => 'name',
                    'expanded' => true,
                    'multiple' => false,
                    'required' => true,
                    'placeholder' => '-- Wybierz coś --',
                )
            )
            ->add('labels', EntityType::class, array(
                    'class' => 'PackageTaskBundle:Labels',
                    'choice_label' => 'name',
                    'expanded' => true,
                    'multiple' => false,
                    'required' => true,
                    'placeholder' => '-- Wybierz coś --',
                    'data_class' => null,
                )
            )
            ->add('save', SubmitType::class, array('label' => 'Send taks'));
    }
}