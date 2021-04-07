<?php

namespace App\DataFixtures;

use App\Entity\Reaction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReactionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($u = 1; $u < 6; $u++)
        {
            for ($r = 1; $r < 101; $r++)
            {
                if (random_int(0, 1)) {
                    $manager->persist(
                        (new Reaction())
                            ->setUser($this->getReference('user_'.$u))
                            ->setResource($this->getReference('resource_'.$r))
                            ->setLiked(random_int(0, 1) === 1)
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
