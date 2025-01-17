<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '50',
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank(),
                ],
            ])
            ->add('time', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                     'min' => '1',
                    'max' => '1441',
                ],
                'label' => 'Temps (en minute)',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                 'constraints' => [ 
                    new Assert\Positive(),
                   new  Assert\LessThan(1441),
                ],
            ])
            ->add('nbPeople', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                      'min' => '1',
                    'max' => '51',
                ],
                'label' => 'Nbre de personne',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                 'constraints' => [ 
                    new Assert\Positive(),
                   new  Assert\LessThan(51),
                ],
            ])
            ->add('difficulty', RangeType::class, [
                'attr' => [
                    'class' => 'form-range',
                      'min' => '1',
                    'max' => '5',
                ],
                'label' => 'Difficulté',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                 'constraints' => [ 
                    new Assert\Positive(),
                   new  Assert\LessThan(5),
                ],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control', 
                ],
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('price',MoneyType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Prix',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                 'constraints' => [ 
                    new Assert\Positive(),
                   new  Assert\LessThan(1001),
                ],
            ])
            ->add('isFavorite', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-range', 
                ],
                'required'=>false,
                'label' => 'Favoris ?',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                 'constraints' => [ 
                    new Assert\NotNull(),
                ],
            ])
            ->add('ingredients', EntityType::class, [
                'class'=>Ingredient::class,
                'query_builder' => function(IngredientRepository $r) {
 return $r->createQueryBuilder('i')->orderBy('i.name', 'ASC');
                },
                'choice_label' => 'name',
                'multiple' =>true,
                'expanded' => true,
                'attr' => [
                    'class' => '', 
                ],
                'label' => 'Ingredients',
                'label_attr' => [
                    'class' => 'form-label mt-4',                       
                ],
                 'constraints' => [ 
                    new Assert\NotNull(),
                ],
            ])
            ->add('submit', SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-primary mt-4',
                ],
                'label' => 'Créer une recette',

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
