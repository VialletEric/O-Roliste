<?php

namespace App\DataFixtures;

use Nelmio\Alice\Loader\NativeLoader;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $loader = new NativeLoader();

       //take fixtures
        $entities = $loader->loadFile(__DIR__.'/fixtures.yml')->getObjects();
        
        foreach ($entities as $entity) {
            $manager->persist($entity);
        };
        $manager->flush();
    }
}
