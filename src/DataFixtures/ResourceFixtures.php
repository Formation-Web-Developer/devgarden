<?php

namespace App\DataFixtures;

use App\Entity\Resource;
use Cocur\Slugify\Slugify;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ResourceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $slugify = new Slugify();

        for ($i=1; $i < 101; $i++)
        {
            $name = $faker->unique()->name;
            $resource = (new Resource())
                    ->setName($name)
                    ->setDescription($faker->text(255))
                    ->setContent($faker->text(2000))
                    ->setSlug($slugify->slugify($name))
                    ->setUser($this->getReference('user_'.random_int(1,5)))
                    ->setCategory($this->getReference('category_'.random_int(1,10)))
                    ->setValidation(random_int(-1,1))
            ;
            $manager->persist($resource);
            $this->addReference('resource_'.$i, $resource);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class
        ];
    }
}
