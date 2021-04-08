<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 1; $i < 101; $i++){
            $comment = (new Comment())
                ->setComment($faker->text(1000))
                ->setAuthor($this->getReference('user_'.random_int(1,5)))
                ->setResource($this->getReference('resource_'.random_int(1,100)));

            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ResourceFixtures::class
        ];
    }
}
