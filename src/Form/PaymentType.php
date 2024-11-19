<?php

namespace App\Form;

use App\Entity\ContableAccount;
use App\Entity\Invoice;
use App\Entity\Payment;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('transactionDate', DateType::class, [
                'attr' => ['class' => 'form-control form-control-sm mb-2'],
                'widget' => 'single_text',
            ])
            ->add('amount', TextType::class, [
                'attr' => ['class' => 'form-control form-control-sm mb-2'],
            ])
            ->add('contableAccount', EntityType::class, [
                'label' => 'Contable Account',
                'attr' => ['class' => 'form-control form-control-sm mb-2'],
                'class' => ContableAccount::class,
                'choice_label' => 'name',
            ])
            ->add('paymentMethod', TextType::class, [
                'attr' => ['class' => 'form-control form-control-sm mb-2'],
            ])
            ->add('notes', TextareaType::class, [
                'attr' => ['class' => 'form-control form-control-sm mb-2'],
                'required' => false
            ])
            ->add('invoice', EntityType::class, [
                'attr' => ['class' => 'form-control form-control-sm mb-2'],
                'class' => Invoice::class,
                'choice_label' => 'documentNumber',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
