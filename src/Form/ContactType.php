<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\ContactAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'Contact Type',
                'attr' => ['class' => 'radio-options'],
                'choices' => [
                    'Individual' => 'Individual',
                    'Company' => 'Company'
                ],
                'data' => 'Individual',
                'expanded' => true,
                'multiple' => false,
                'label_attr' => ['class' => 'mb-0']
            ])
            ->add('principalSalutation', ChoiceType::class, [
                'label' => 'Salutation',
                'choices' => [
                    'Mr.' => 'Mr.',
                    'Ms.' => 'Ms.',
                    'Miss.' => 'Miss.',
                    'Dr.' => 'Dr.',
                ],
                'attr' => ['class' => 'form-control form-control-sm mb-2 contact-salutation displayname-trigger'],
                'required' => false,
                'placeholder' => 'Salutation'
            ])
            ->add('principalFirstName', TextType::class, [
                'label' => 'First Name',
                'attr' => ['class' => 'form-control form-control-sm mb-2 contact-firstname displayname-trigger', 'placeholder' => 'First Name'],
                'required' => false
            ])
            ->add('principalLastName', TextType::class, [
                'label' => 'Last Name',
                'attr' => ['class' => 'form-control form-control-sm mb-2 contact-lastname displayname-trigger', 'placeholder' => 'Last Name'],
                'required' => false
            ])
            ->add('companyName', TextType::class, [
                'label' => 'Company Name',
                'attr' => ['class' => 'form-control form-control-sm mb-2 contact-company displayname-trigger'],
                'required' => false
            ])
//            ->add('displayName', ChoiceType::class, [
//                'label' => 'Display Name',
//                'attr' => ['class' => 'form-control form-control-sm mb-2 contact-displayname form-picker displayname-form-picker',],
//                'constraints' => [
//                    new Choice([
//                        'choices' => ['Option 1', 'Option 2', 'Option 3'], // Las opciones válidas
//                        'message' => 'The selected choice is invalid.',
//                        'strict' => false, // Permite valores fuera de esta lista
//                    ]),
//                ]
//            ])
            ->add('emailAddress', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control form-control-sm mb-2'],
                'required' => false
            ])
            ->add('workPhoneNumber', TextType::class, [
                'label' => 'Work Phone',
                'row_attr' => ['class' => 'input-group mb-2'],
                'attr' => [
                    'class' => 'form-control form-control-sm',
                    'placeholder' => 'Work Phone Number'
                ],
                'required' => false
            ])
            ->add('mobilePhoneNumber', TextType::class, [
                'label' => 'Mobile Phone',
                'row_attr' => ['class' => 'input-group mb-2'],
                'attr' => [
                    'class' => 'form-control form-control-sm',
                    'placeholder' => 'Mobile Phone Number'
                ],
                'required' => false
            ])
            ->add('contactAddresses', CollectionType::class, [
                'entry_type' => ContactAddressType::class,
                'entry_options' => ['label' => false],
                'by_reference' => false,
                'prototype' => true,
                'prototype_name' => '__name__',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
