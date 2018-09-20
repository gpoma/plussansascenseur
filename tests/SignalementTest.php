<?php

namespace Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Yaml\Yaml;

class Signalement extends KernelTestCase
{
    private $container;
    private $odm;

    public function setUp()
    {
        $kernel = self::bootKernel();

        $this->container = $kernel->getContainer();
        $this->odm->get('doctrine_mongodb.odm.document_manager');
    }

    public function test()
    {
        $signalement = new Signalement();



        $odm->persist($signalement);
        $odm->flush();

        $this->assertSame($signalement->getId());
    }

}
