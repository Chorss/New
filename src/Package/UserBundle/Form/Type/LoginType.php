<?php

namespace Package\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LoginType extends AbstractType
{
    public function getName()
    {
        return 'login';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'label' => 'Login'
            ))
            ->add('password', PasswordType::class, array(
                'label' => 'Password'
            ))
            ->add('remember_me', CheckboxType::class, array(
                'label' => 'Remember me',
                'required' => false,
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Login in'
            ));
    }
}