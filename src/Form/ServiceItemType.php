<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control form-control-sm mb-2'],
            ])
            ->add('unit', TextType::class, [
                'attr' => ['class' => 'form-control form-control-sm mb-2'],
                'required' => false
            ])
            ->add('regularPrice', NumberType::class, [
                'attr' => ['class' => 'form-control form-control-sm mb-2']
            ])
            ->add('description', TextType::class, [
                'attr' => ['class' => 'form-control form-control-sm mb-2'],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
