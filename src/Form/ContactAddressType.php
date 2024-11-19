<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\ContactAddress;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('attention', TextType::class, [
                'label' => 'Attention',
                'attr' => ['class' => 'form-control form-control-sm mb-2', 'placeholder' => 'In Care Of'],
                'required' => false
            ])
            ->add('line1', TextType::class, [
                'label' => 'Address Line 1',
                'attr' => ['class' => 'form-control form-control-sm mb-2', 'placeholder' => 'Address Line 1'],
                'required' => false
            ])
            ->add('line2', TextType::class, [
                'label' => 'Address Line 2',
                'attr' => ['class' => 'form-control form-control-sm mb-2', 'placeholder' => 'Address Line 2'],
                'required' => false
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
                'attr' => ['class' => 'form-control form-control-sm mb-2', 'placeholder' => 'City'],
                'required' => false
            ])
            ->add('state', TextType::class, [
                'label' => 'State',
                'attr' => ['class' => 'form-control form-control-sm mb-2', 'placeholder' => 'State'],
                'required' => false
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'Zip Code',
                'attr' => ['class' => 'form-control form-control-sm mb-2', 'placeholder' => 'Zip Code'],
                'required' => false
            ])
            ->add('country', TextType::class, [
                'label' => 'Country',
                'attr' => ['class' => 'form-control form-control-sm mb-2', 'placeholder' => 'Country'],
                'required' => false
            ])
            ->add('fax', TextType::class, [
                'label' => 'Fax Number',
                'attr' => ['class' => 'form-control form-control-sm mb-2', 'placeholder' => 'Fax Number'],
                'required' => false
            ])
            ->add('phone', TextType::class, [
                'label' => 'Phone Number',
                'attr' => ['class' => 'form-control form-control-sm mb-2', 'placeholder' => 'Phone Number'],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactAddress::class,
        ]);
    }
}
