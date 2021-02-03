<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',
            TextType::class,
                [
                'constraints' =>
                    [
                        new NotBlank(['message' => 'Please enter a password']),
                    ]
                ]
            )
            ->add('password',
                PasswordType::class,
                [
                'constraints' =>
                    [
                    new NotBlank(['message' => 'Please enter a password',]),
                    new Length(
                        [
                            'min' => 6,
                            'max' => 20,
                        ]),
                    ]
                ]
            );
    }
}
