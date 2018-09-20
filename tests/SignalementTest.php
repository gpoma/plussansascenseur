<?php

namespace Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Yaml\Yaml;
use AppBundle\Document\Signalement;

class SignalementTest extends KernelTestCase
{
    private $container;
    private $odm;

    public function setUp()
    {
        self::bootKernel();

        $this->container = self::$kernel->getContainer();
        $this->odm = $this->container->get('doctrine_mongodb.odm.document_manager');
    }

    public function test()
    {
        $signalement = new Signalement();



        $this->odm->persist($signalement);
        $this->odm->flush();

        $this->assertSame($signalement->getId());
    }

}
