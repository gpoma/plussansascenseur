<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Document\Photo;
use AppBundle\Type\PhotoType;
use AppBundle\Type\SignalementType;
use AppBundle\Document\Signalement;
use AppBundle\Document\Ascenseur;
use AppBundle\Lib\AdresseDataGouvApi;

class DefaultController extends Controller
{
    /**
     * Page d'accueil de l'application
     * Permet de fournir une photo d'un ascenseur en panne
     * Optionnellement, permet le signalement sans photo
     *
     * Contient des informations sur le site
     *
     * @Route("/", name="homepage")
     *
     * @param Request $request La requête
     * @return Response La réponse
     */
    public function indexAction(Request $request)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $photo = new Photo();
        $uploadPhotoForm = $this->createForm(PhotoType::class, $photo, array(
            'action' => $this->generateUrl('photo_upload'),
            'method' => 'POST'
        ));

        return $this->render('default/index.html.twig', ["uploadPhotoForm" => $uploadPhotoForm->createView()]);
    }


    /**
     * Récupère la géolocalisation de l'appareil
     *
     * @Route("/localisation", name="localisation")
     *
     * @param Request $request La requête
     * @return Response La réponse
     */
    public function localisationAction(Request $request)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();

        $coordinates = $request->get('coordinates', null);
        $photoid = $request->get('photo', null);
        $address = null;

        if ($coordinates) {
            $coordinates = urldecode($coordinates);
            return $this->redirect($this->generateUrl('nearby', array('photo' => $photo->getId(), 'coordinates' => $coordinates)));
        }

        return $this->render('default/localisation.html.twig', compact('coordinates', 'photoid', 'address'));
    }

    /**
     * Recherche les ascenseurs à proximité pour éviter les doublons
     *
     * @Route("/nearby", name="nearby")
     *
     * @param Request $request La requête
     * @return Response La réponse
     */
    public function nearbyAction(Request $request)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();

        $coordinates = $request->get('coordinates', null);
        $photoid = $request->get('photo', null);
        $address = null;
        $elevators = array();

        if ($photoid && !$coordinates) {
            if ($photo = $dm->getRepository(Photo::class)->findOneById($photoid)) {
                if ($localisation = $photo->getLocalisation()) {
                    $coordinates = $localisation->getCoordinatesLibelle();
                }
            }
        }

        if ($coordinates) {
            $coordinates = urldecode($coordinates);
            $address = AdresseDataGouvApi::getAddrByCoordinates($coordinates);
            $elevators = $dm->getRepository(Ascenseur::class)->findByCoordinates($coordinates);
        }

        return $this->render('default/nearby.html.twig', compact('coordinates', 'address', 'photoid', 'elevators'));
    }

    /**
     * Affiche un formulaire à remplir sur les problèmes rencontrés avec l'ascenseur
     *
     * @Route("/signalement", name="signalement")
     *
     * @param Request $request La requête
     * @return Response La réponse
     */
    public function signalementAction(Request $request)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $coordinates = $request->get('coordinates', null);
        $coordinatesArr = null;

        if ($coordinates) $coordinatesArr = explode(",",urldecode($coordinates));

        $ascenseur = new Ascenseur();
        if ($request->get('ascenseur')) {
            $ascenseur = $dm->getRepository(Ascenseur::class)->find($request->get('ascenseur'));
        }

        if ($coordinatesArr && count($coordinatesArr) === 2) {
            $ascenseur->setLatLon($coordinatesArr[1], $coordinatesArr[0]);
        }

        $signalement = new Signalement($ascenseur);

        if ($request->get('photo')) {
            $photo = $dm->getRepository(Photo::class)->find($request->get('photo'));
            $ascenseur->addPhoto($photo);
        }

        $form = $this->createForm(SignalementType::class, $signalement, ['method' => Request::METHOD_POST]);

        if(! $request->isMethod(Request::METHOD_POST)) {
            return $this->render('default/signalement.html.twig', ["form" => $form->createView()]);
        }

        $form->handleRequest($request);

        if(! $form->isSubmitted() || ! $form->isValid()) {
            return $this->render('default/signalement.html.twig', ["form" => $form->createView()]);
        }

        $dm->persist($ascenseur);

        $dm->getRepository(Ascenseur::class)
           ->saveVersion($ascenseur, new \DateTime(), "Création de l'ascenseur", $signalement->getPseudo());

        $signalement->createEnPanne();
        $dm->persist($signalement);
        $dm->flush();

        return $this->redirect($this->generateUrl('ascenseur', ['id' => $ascenseur->getId()]));
    }
}
