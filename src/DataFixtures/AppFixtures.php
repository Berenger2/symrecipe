<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');

    }

    public function load(ObjectManager $manager): void
    {

        // Ingredients
        $ingredients = [];
        for ($i = 1; $i < 50; $i++) {
            $ingredient = new Ingredient;
            $ingredient->setName($this->faker->word())
                ->setPrice($this->faker->numberBetween(0, 100));
            $ingredients[] = $ingredient;

            $manager->persist($ingredient);
        }

        // Recipes
        $recipes = [];
        for ($j = 1; $j < 25; $j++) {
            $recipe = new Recipe;
            $recipe->setName($this->faker->word())
                ->setTime(mt_rand(0, 1) == 1 ? mt_rand(1, 1440) : null)
                ->setNbPeople(mt_rand(0, 1) == 1 ? mt_rand(1, 50) : null)
                ->setDescription($this->faker->text(300))
                ->setDifficulty(mt_rand(0, 1) == 1 ? mt_rand(1, 5) : null)
                ->setIsFavorite(mt_rand(0, 1) == 1 ? true : false)
                ->setPrice(mt_rand(0, 1) == 1 ? mt_rand(1, 1000) : null);
            for ($k = 0; $k < mt_rand(5, 15); $k++) {
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }
            // $recipes[] = $recipe;
            $manager->persist($recipe);
        }

        $manager->flush();
    }
}
