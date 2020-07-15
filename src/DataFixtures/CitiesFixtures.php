<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CitiesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
         $marseille = new City();
         $marseille->setName('Marseille');

        $lyon = new City();
        $lyon->setName('Lyon');
         $manager->persist($marseille);
         $manager->persist($lyon);

        $manager->flush();
    }
}