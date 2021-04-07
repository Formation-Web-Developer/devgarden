<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Cocur\Slugify\Slugify;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $slugify = new Slugify();

        for ($i = 1; $i < 11; $i++) {
            $name = $faker->unique()->name;
            $category = (new Category())
                ->setName($name)
                ->setDescription($faker->text(255))
                ->setSlug($slugify->slugify($name));
            $manager->persist($category);
            $this->addReference('category_'.$i, $category);
        }

        $manager->flush();
    }
}
