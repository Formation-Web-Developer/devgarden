<?php

namespace App\DataFixtures;

use App\Entity\Resource;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ResourceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        for ($i=1; $i < 101; $i++)
        {
            $manager->persist(
                (new Resource())
                    ->setName('Fallen Kingdoms '.$i)
                    ->setDescription('Ceci est un plugin')
                    ->setContent('Fallen Kingdoms est un plugin minecraft incroyable !')
                    ->setSlug('fallen-kingdoms-'.$i)
                    ->setUser($this->getReference('user_'.random_int(1,5)))
                    ->setCategory($this->getReference('category_'.random_int(1,2)))
            );
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
