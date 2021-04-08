<?php

namespace App\DataFixtures;

use App\Entity\SubscribeResource;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SubscribeResourceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($x = 1; $x < 6; $x++) {
            for ($y = 1; $y < 101; $y++) {
                if (random_int(1, 3) === 3) {
                    $manager->persist(
                        (new SubscribeResource())
                            ->setUser($this->getReference('user_'.$x))
                            ->setResource($this->getReference('resource_'.$y))
                    );
                }
            }
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
