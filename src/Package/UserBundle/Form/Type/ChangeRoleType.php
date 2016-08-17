<?php
namespace Package\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ChangeRoleType extends AbstractType
{
    public function getName()
    {
        return "changeRole";
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'label' => 'Username',
                'constraints' => array(
                    new Assert\NotBlank()
                )
            ))
            ->add('role', ChoiceType::class, array(
                'choices'  => array(
                    'Admin' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER',
                    ),
                'choices_as_values' => true,
                ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Submit'
            ));
    }
}