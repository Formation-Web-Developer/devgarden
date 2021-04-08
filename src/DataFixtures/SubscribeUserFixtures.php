<?php

namespace App\DataFixtures;

use App\Entity\SubscribeUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SubscribeUserFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($x = 1; $x < 6; $x++) {
            for ($y = 1; $y < 6; $y++) {
                if ($y !== $x && random_int(0, 1)) {
                    $manager->persist(
                        (new SubscribeUser())
                            ->setUser($this->getReference('user_'.$x))
                            ->setSubscribed($this->getReference('user_'.$y))
                    );
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
