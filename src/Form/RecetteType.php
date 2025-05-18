<?php

namespace App\Form;

use App\Entity\Recette;
use App\Entity\Ingredient;
use App\Entity\Step;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la recette',
                'attr' => [
                    'placeholder' => 'Ex: Gâteau au chocolat',
                    'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Décrivez votre recette...',
                    'rows' => 4,
                    'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500'
                ]
            ])
            ->add('image', UrlType::class, [
                'label' => 'Image de la recette',
                'required' => false,
                'attr' => [
                    'placeholder' => 'URL de l\'image',
                    'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500'
                ]
            ])
            ->add('preparationTime', IntegerType::class, [
                'label' => 'Temps de préparation (en minutes)',
                'attr' => [
                    'min' => 1,
                    'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500'
                ]
            ])
            ->add('difficulty', ChoiceType::class, [
                'label' => 'Difficulté',
                'choices' => [
                    'Facile' => 'Facile',
                    'Moyen' => 'Moyen',
                    'Difficile' => 'Difficile'
                ],
                'attr' => [
                    'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500'
                ]
            ])
            ->add('ingredients', CollectionType::class, [
                'entry_type' => IngredientType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'attr' => [
                    'class' => 'ingredients-collection'
                ]
            ])
            ->add('steps', CollectionType::class, [
                'entry_type' => StepType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'attr' => [
                    'class' => 'steps-collection'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
} 