<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\Invoice;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('documentNumber', TextType::class, [
                'attr' => ['class' => 'form-control form-control-sm mb-2'],
                'label' => 'Quote No.'
            ])
            ->add('referenceNumber', TextType::class, [
                'attr' => ['class' => 'form-control form-control-sm mb-2'],
                'label' => 'Reference No.',
                'required' => false
            ])
            ->add('documentDate', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control form-control-sm mb-2'],
            ])
            ->add('expiryDate', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control form-control-sm mb-2'],
                'required' => false
            ])
            ->add('customer', EntityType::class, [
                'class' => Contact::class,
                'choice_label' => 'displayName',
                'attr' => ['class' => 'form-control form-control-sm mb-2'],
                'label' => 'Customer Name'
            ])
            ->add('salesPerson', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'attr' => ['class' => 'form-control form-control-sm mb-2'],
                'required' => false
            ])
            ->add('invoiceItems', CollectionType::class, [
                'entry_type' => InvoiceItemType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'prototype_name' => '__name__',
                'attr' => ['class' => 'item-collection']
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
