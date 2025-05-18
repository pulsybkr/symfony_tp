<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Ingrédient',
                'attr' => [
                    'placeholder' => 'Nom de l\'ingrédient',
                    'class' => 'ingredient-name w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500',
                    'data-autocomplete' => 'ingredients'
                ]
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'Quantité',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Quantité',
                    'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500'
                ]
            ])
            ->add('unit', ChoiceType::class, [
                'label' => 'Unité',
                'required' => false,
                'choices' => [
                    'g' => 'g',
                    'kg' => 'kg',
                    'ml' => 'ml',
                    'cl' => 'cl',
                    'l' => 'l',
                    'cuillère à soupe' => 'cuillère à soupe',
                    'cuillère à café' => 'cuillère à café',
                    'pincée' => 'pincée',
                    'unité' => 'unité'
                ],
                'attr' => [
                    'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
} 