<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Recette;
use App\Entity\Ingredient;
use App\Entity\Step;
use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setUsername($faker->userName());
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
            $user->setIsVerified(true);
            $user->setProfilePicture('https://picsum.photos/150/150?random=' . $faker->uuid());

            $manager->persist($user);
            $users[] = $user;
        }

        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setIsVerified(true);
        $admin->setProfilePicture('https://picsum.photos/150/150?random=admin');

        $manager->persist($admin);
        $users[] = $admin;

        $difficulties = ['Facile', 'Moyen', 'Difficile'];

        for ($i = 0; $i < 20; $i++) {
            $recette = new Recette();
            $recette->setTitle($faker->sentence(3));
            $recette->setDescription($faker->paragraph(3));
            $recette->setPreparationTime($faker->numberBetween(15, 180));
            $recette->setDifficulty($faker->randomElement($difficulties));
            $recette->setImage('https://picsum.photos/640/480?random=' . rand(1, 1000));
            $recette->setAuthor($faker->randomElement($users));
            $recette->setCreateAt($faker->dateTimeBetween('-1 year', 'now'));
            $recette->setUpdateAt($faker->optional(0.3)->dateTimeBetween('-1 year', 'now'));

            for ($j = 0; $j < $faker->numberBetween(3, 8); $j++) {
                $ingredient = new Ingredient();
                $ingredient->setName($faker->word());
                $ingredient->setQuantity($faker->numberBetween(1, 500));
                $ingredient->setUnit($faker->randomElement(['g', 'kg', 'ml', 'cl', 'l', 'cuillère à soupe', 'cuillère à café', 'pincée']));
                $ingredient->setRecette($recette);
                $manager->persist($ingredient);
            }

            for ($k = 0; $k < $faker->numberBetween(3, 8); $k++) {
                $step = new Step();
                $step->setPosition($k + 1);
                $step->setDescription($faker->paragraph(2));
                $step->setImage('https://picsum.photos/640/480?random=' . rand(1, 1000));
                $step->setRecette($recette);
                $manager->persist($step);
            }

            for ($l = 0; $l < $faker->numberBetween(0, 5); $l++) {
                $review = new Review();
                $review->setRating($faker->numberBetween(1, 5));
                $review->setComment($faker->optional(0.7)->paragraph());
                $review->setAuthor($faker->randomElement($users));
                $review->setRecette($recette);
                $review->setCreateAt($faker->dateTimeBetween('-1 year', 'now'));
                $review->setImage('https://picsum.photos/640/480?random=' . rand(1, 1000));
                $manager->persist($review);
            }

            $manager->persist($recette);
        }

        $manager->flush();
    }
}
