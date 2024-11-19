<?php

namespace App\Form;

use App\Entity\ContableAccount;
use App\Entity\Contact;
use App\Entity\Invoice;
use App\Entity\JournalEntry;
use App\Entity\Payment;
use App\Entity\Project;
use App\Entity\TransactionCategory;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('uuid')
            ->add('entryDate', null, [
                'widget' => 'single_text',
            ])
            ->add('description')
            ->add('currency')
            ->add('debit')
            ->add('credit')
            ->add('balance')
            ->add('paymentMethod')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('contact', EntityType::class, [
                'class' => Contact::class,
                'choice_label' => 'id',
            ])
            ->add('invoice', EntityType::class, [
                'class' => Invoice::class,
                'choice_label' => 'id',
            ])
            ->add('employee', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'id',
            ])
            ->add('contableAccount', EntityType::class, [
                'class' => ContableAccount::class,
                'choice_label' => 'id',
            ])
            ->add('transactionCategory', EntityType::class, [
                'class' => TransactionCategory::class,
                'choice_label' => 'id',
            ])
            ->add('associatedPayment', EntityType::class, [
                'class' => Payment::class,
                'choice_label' => 'id',
            ])
            ->add('createdBy', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('updatedBy', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JournalEntry::class,
        ]);
    }
}
