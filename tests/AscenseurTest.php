<?php

namespace Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Yaml\Yaml;
use AppBundle\Document\Ascenseur;

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

        
    }

    public function testForm() {
        // $form = $this->formFactory->create(SignalementType::class, new Signalement());
        //
        // $form->submit(array(
        //                 'etage' => "9",
        //                 'usage' => 'HABITANT',
        //                 'etageAtteint' => "0",
        //                 'commentaire' => "Je ne suis pas content",
        //                 'abonnement' => "1",
        //                 'duree' => "30 minutes",
        //                 'pseudo' => 'test',
        //                 'email' => 'contact@24eme.fr',
        //                 'telephone' => '0102030405',
        //               ));
        //
        // $this->assertTrue($form->isSubmitted(), true);
        // $this->assertTrue($form->isValid(), true);
    }

}
