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
        $ascenseur->setAdresse("102 rue des Poissonniers");
        $ascenseur->setCodePostal("75018");
        $ascenseur->setCommune("Paris");
        $ascenseur->setEmplacement("Batiment A Ascenseur de droite");
        $ascenseur->setReference("123456789");
        $ascenseur->setConstructeurNom("Otis");
        $ascenseur->setBailleurNom("Bailleur");
        $ascenseur->setSyndicNom("Syndic");
        $ascenseur->setAscensoristeNom("Ascensoriste");
        $ascenseur->setEtageMin(-2);
        $ascenseur->setEtageMax(9);
        $ascenseur->setTelephoneDepannage("0102030405");
        $ascenseur->setDateConstruction("1980-11-08");
        $ascenseur->setDateRenovation("2011-08-11");

        $this->odm->persist($ascenseur);
        $this->odm->flush();

        $this->assertNotNull($ascenseur->getId());
        $this->assertEquals($ascenseur->getStatut(), Ascenseur::STATUT_ENPANNE);
        $this->assertNotNull($ascenseur->getLocalisation());
        $this->assertEquals($ascenseur->getLocalisation()->getCoordinates()->getX(), $lon);
        $this->assertEquals($ascenseur->getLocalisation()->getCoordinates()->getY(), $lat);
        $this->assertEquals($ascenseur->getAdresse(), "102 rue des Poissonniers");
        $this->assertEquals($ascenseur->getCodePostal(), "75018");
        $this->assertEquals($ascenseur->getCommune(), "Paris");
        $this->assertEquals($ascenseur->getEmplacement(), "Batiment A Ascenseur de droite");
        $this->assertEquals($ascenseur->getReference(), "123456789");
        $this->assertEquals($ascenseur->getConstructeurNom(), "Otis");
        $this->assertEquals($ascenseur->getBailleurNom(), "Bailleur");
        $this->assertEquals($ascenseur->getSyndicNom(), "Syndic");
        $this->assertEquals($ascenseur->getAscensoristeNom(), "Ascensoriste");
        $this->assertEquals($ascenseur->getEtageMin(), -2);
        $this->assertEquals($ascenseur->getEtageMax(), 9);
        $this->assertEquals($ascenseur->getTelephoneDepannage(), "0102030405");
        $this->assertEquals($ascenseur->getDateConstruction(), "1980-11-08");
        $this->assertEquals($ascenseur->getDateRenovation(), "2011-08-11");
        $this->assertEquals($ascenseur->getTelephoneDepannage(), "0102030405");
        $this->assertEquals($ascenseur->getUpdatedAt()->format('Y-m-d'), date('Y-m-d'));

        $ascenseur = $this->odm->find('AppBundle\Document\Ascenseur', $ascenseur->getId());
        $nbPhotos = 5;
        for ($i=0; $i < $nbPhotos; $i++) {
            $photo = new Photo();
            $photo->setLatLon($lat,$lon);
            $photo->setBase64(base64_encode(file_get_contents(realpath("web/psa_logo_300x300.png"))));
            $this->odm->persist($photo);
            $this->assertEquals($photo->getUpdatedAt()->format('Y-m-d'), date('Y-m-d'));
            $photo->setAscenseur($ascenseur);
        }
        $this->odm->flush();

        $photos = $this->odm->getRepository('AppBundle:Photo')->findAll();
        $ascenseur = $this->odm->getRepository('AppBundle:Ascenseur')->find($ascenseur->getId());

        $this->assertTrue(count($photos) >= $nbPhotos);

        $ascenseur->createVersion("vincent");
        $ascenseur->addEvenement(new \DateTime(), "Signalé en panne", "Otis redding");

        $this->odm->flush();

        $this->assertCount(2, $ascenseur->getHistorique());
        $this->assertEquals($ascenseur->getHistorique()[1]->getCommentaire(), "Des informations sur l'ascenseur ont été complétées");

        $this->odm->flush();
    }

}
