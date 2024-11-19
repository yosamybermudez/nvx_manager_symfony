<?php

namespace App\Form;

use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Entity\Item;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('item', EntityType::class, [
                'class' => Item::class,
                'choice_label' => 'referenceName',
                'choice_attr' => function($choice, $key, $value) { return ['data-price' => $choice->getRegularPrice()]; },
                'attr' => ['class' => 'form-control form-control-sm mb-2 ii-item ii-variable form-picker'],
                'placeholder' => 'Type or select an option'
            ])
            ->add('quantity', TextType::class, [
                'attr' => ['class' => 'form-control form-control-sm mb-2 text-right ii-quantity ii-variable'],
                'data' => 1
            ])
            ->add('notes', TextareaType::class, [
                'attr' => ['class' => 'form-control form-control-sm mb-2'],
            ])
            ->add('rate', TextType::class, [
                'attr' => ['class' => 'form-control form-control-sm mb-2 text-right ii-rate ii-variable'],
            ])
            ->add('amount', HiddenType::class, [
                'attr' => ['class' => 'form-control form-control-sm mb-2 ii-amount '],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InvoiceItem::class,
        ]);
    }
}
