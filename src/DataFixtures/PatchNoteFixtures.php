<?php

namespace App\DataFixtures;

use App\Entity\PatchNote;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PatchNoteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $slugify = new Slugify();

        for ($x=1; $x < 101; $x++)
        {
            $n = random_int(1,10);
            for ($i=1; $i <= $n; $i++)
            {
                $name = $faker->unique()->name;
                $resource = $this->getReference('resource_'.$x);
                $patchNote = (new PatchNote())
                    ->setVersion($faker->ipv4)
                    ->setDescription($faker->text(255))
                    ->setContent($faker->text(2000))
                    ->setSlug($slugify->slugify($name))
                    ->setLink($faker->text(10))
                    ->setResource($resource)
                ;
                if ($i === $n) {
                    $resource->setLatest($patchNote);
                    $manager->persist($resource);
                }
                $manager->persist($patchNote);

            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ResourceFixtures::class
        ];
    }
}
