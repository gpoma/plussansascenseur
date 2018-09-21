<?php

namespace Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Yaml\Yaml;
use AppBundle\Document\Ascenseur;
use AppBundle\Document\Photo;

class AscenseurTest extends KernelTestCase
{
    private $container;
    private $odm;

    public function setUp()
    {
        self::bootKernel();

        $this->container = self::$kernel->getContainer();
        $this->odm = $this->container->get('doctrine_mongodb.odm.document_manager');
        $this->formFactory = $this->container->get('form.factory');
    }

    public function testDocument()
    {
        $ascenseur = new Ascenseur();

        $lat = 48.8934163;
        $lon = 2.3530048999999735;

        $ascenseur->setLatLon($lat,$lon);

        $this->odm->persist($ascenseur);
        $this->odm->flush();

        $this->assertNotNull($ascenseur->getId());
        $this->assertEquals($ascenseur->getStatut(), Ascenseur::STATUT_ENPANNE);
        $this->assertNotNull($ascenseur->getLocalisation());
        $this->assertEquals($ascenseur->getLocalisation()->getCoordinates()->getX(), $lon);
        $this->assertEquals($ascenseur->getLocalisation()->getCoordinates()->getY(), $lat);

        $ascenseur = $this->odm->find('AppBundle\Document\Ascenseur', $ascenseur->getId());
        $nbPhotos = 5;
        for ($i=0; $i < $nbPhotos; $i++) {
            $photo = new Photo();
            $photo->setLatLon($lat,$lon);
            $photo->setBase64(base64_encode(file_get_contents(realpath("web/psa_logo_300x300.png"))));
            $this->odm->persist($photo);
            $photo->setAscenseur($ascenseur);
        }
        $this->odm->flush();

        $photos = $this->odm->getRepository('AppBundle:Photo')->findAll();
        $ascenseur = $this->odm->getRepository('AppBundle:Ascenseur')->find($ascenseur->getId());

        $this->assertTrue(count($photos) >= $nbPhotos);


    }

    public function testForm() {
    }

}
