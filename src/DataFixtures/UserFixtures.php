<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = (new User())
                ->setName('Admin')
                ->setEmail('admin@devgarden.fr')
                ->setIsVerified(true)
                ->setRoles(['ROLE_SUPER_ADMIN'])
        ;
        $user->setPassword($this->encoder->encodePassword($user,'formationwebdeveloper'));
        $manager->persist($user);

        for ($i = 1; $i < 6; $i++ ){
            $user = (new User())
                ->setName('Demo '. $i)
                ->setEmail('demo'.$i.'@devgarden.fr')
                ->setIsVerified(true)
            ;
            $user->setPassword($this->encoder->encodePassword($user,'formationwebdeveloper'));
            $manager->persist($user);
            $this->addReference('user_'.$i,$user);
        }
        // antoine
        $antoine = (new User())
            ->setName('antoine')
            ->setEmail('quidelantoine@gmail.com')
            ->setIsVerified(true)
            ->setRoles(['ROLE_ADMIN'])
        ;
        $antoine->setPassword($this->encoder->encodePassword($antoine,'michel'));
        $manager->persist($antoine);

        $manager->flush();
    }
}
