<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $category = (new Category())
            ->setName('Plugins')
            ->setDescription('Liste des plugins')
            ->setSlug('plugins');
        $manager->persist($category);
        $this->addReference('category_1',$category);


        $category = (new Category())
            ->setName('Mods')
            ->setDescription('Liste des mods')
            ->setSlug('mods');
        $manager->persist($category);
        $this->addReference('category_2',$category);

        $manager->flush();
    }
}
