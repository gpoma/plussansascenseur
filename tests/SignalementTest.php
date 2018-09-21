<?php

namespace Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Yaml\Yaml;
use AppBundle\Document\Signalement;
use AppBundle\Document\Ascenseur;
use AppBundle\Type\SignalementType;

class SignalementTest extends KernelTestCase
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
        $signalement = new Signalement($ascenseur);

        $datePanne = new \DateTime();
        $datePanne->modify('-3 days');


        $signalement->setDate(new \DateTime());
        $signalement->setDatePanne($datePanne);
        $signalement->setEtage("8");
        $signalement->setUsage("HABITATION");
        $signalement->setEtageAtteint(true);
        $signalement->setDuree("45 minutes");
        $signalement->setCommentaire("J'ai la jambe cassé");
        $signalement->setAbonnement(true);
        $signalement->setPseudo("test");
        $signalement->setEmail("contact@24eme.fr");
        $signalement->setTelephone("0102030405");

        $signalement->createEvenement();

        $this->odm->persist($ascenseur);
        $this->odm->persist($signalement);
        $this->odm->flush();

        $this->assertNotNull($signalement->getId());
        $this->assertEquals($signalement->getAscenseur()->getId(), $ascenseur->getId());
        $this->assertEquals($signalement->getDate()->format('Y-m-d'), (new \DateTime())->format('Y-m-d'));
        $this->assertEquals($signalement->getDatePanne()->format('Y-m-d'), $datePanne->format('Y-m-d'));
        $this->assertEquals($signalement->getEtage(), "8");
        $this->assertEquals($signalement->getUsage(), "HABITATION");
        $this->assertEquals($signalement->getEtageAtteint(), true);
        $this->assertEquals($signalement->getDuree(), "45 minutes");
        $this->assertEquals($signalement->getCommentaire(), "J'ai la jambe cassé");
        $this->assertEquals($signalement->getAbonnement(), true);
        $this->assertEquals($signalement->getPseudo(), "test");
        $this->assertEquals($signalement->getEmail(), "contact@24eme.fr");
        $this->assertEquals($signalement->getTelephone(), "0102030405");
        $this->assertCount(1, $signalement->getAscenseur()->getHistorique());
    }

    public function testForm() {
        $ascenseur = new Ascenseur();
        $signalement = new Signalement($ascenseur);

        $form = $this->formFactory->create(SignalementType::class, $signalement);

        $form->submit(array(
                        'etage' => "9",
                        'usage' => 'HABITANT',
                        'etageAtteint' => "0",
                        'duree' => "30 minutes",
                        'commentaire' => "Je ne suis pas content",
                        'abonnement' => "1",
                        'pseudo' => 'test',
                        'email' => 'contact@24eme.fr',
                        'telephone' => '0102030405',
                      ));

        $this->assertTrue($form->isSubmitted(), true);
        $this->assertTrue($form->isValid(), true);

        $this->assertEquals($signalement->getDate()->format('Y-m-d'), (new \DateTime())->format('Y-m-d'));
        $this->assertEquals($signalement->getEtage(), "9");
        $this->assertEquals($signalement->getUsage(), "HABITANT");
        $this->assertEquals($signalement->getEtageAtteint(), false);
        $this->assertEquals($signalement->getDuree(), "30 minutes");
        $this->assertEquals($signalement->getCommentaire(), "Je ne suis pas content");
        $this->assertEquals($signalement->getAbonnement(), true);
        $this->assertEquals($signalement->getPseudo(), "test");
        $this->assertEquals($signalement->getEmail(), "contact@24eme.fr");
        $this->assertEquals($signalement->getTelephone(), "0102030405");
    }

}
