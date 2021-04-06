<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $manager->persist(
            (new Category())
                ->setName('Plugins')
                ->setDescription('Liste des plugins')
                ->setSlug('plugins')
        );
        $manager->persist(
            (new Category())
                ->setName('Mods')
                ->setDescription('Liste des mods')
                ->setSlug('mods')
        );

        $manager->flush();
    }
}
