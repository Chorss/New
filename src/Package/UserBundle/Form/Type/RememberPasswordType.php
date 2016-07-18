<?php

namespace Package\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RememberPasswordType extends AbstractType
{
    public function getName()
    {
        return 'rememberPassword';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
            'label' => 'Email',
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Email()
            )
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Submit'
            ));
    }
}