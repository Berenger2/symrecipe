<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
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

        for ($i = 1; $i < 50; $i++) {
            $ingredient = new Ingredient;
            $ingredient->setName($this->faker->word())
                ->setPrice($this->faker->numberBetween(0, 100));
            $manager->persist($ingredient);
        }

        $manager->flush();
    }
}