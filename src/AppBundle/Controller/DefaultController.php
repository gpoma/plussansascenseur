<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Document\Photo;
use AppBundle\Type\PhotoType;
use AppBundle\Type\SignalementType;
use AppBundle\Type\AscenseurType;
use AppBundle\Document\Signalement;
use AppBundle\Document\Ascenseur;
use AppBundle\Lib\AdresseDataGouvApi;
use AppBundle\Repository\AscenseurRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $photo = new Photo();
        $uploadPhotoForm = $this->createForm(PhotoType::class, $photo, array(
            'action' => $this->generateUrl('photo_upload'),
            'method' => 'POST'
        ));

        return $this->render('default/index.html.twig',array("uploadPhotoForm" => $uploadPhotoForm->createView()));
    }

    /**
     * @Route("/photo/upload", name="photo_upload")
     */
    public function photoUploadAction(Request $request) {
        if (!$request->isMethod('POST')) {

            return $this->redirect($this->generateUrl('homepage'));
        }
        $dm = $this->get('doctrine_mongodb')->getManager();
        $photo = new Photo();

        $uploadPhotoForm = $this->createForm(PhotoType::class, $photo, array(
           'action' => $this->generateUrl('photo_upload'),
           'method' => 'POST',
        ));

        $uploadPhotoForm->handleRequest($request);

        if(!$uploadPhotoForm->isValid()) {

            return $this->render('default/index.html.twig',array("uploadPhotoForm" => $uploadPhotoForm->createView()));
        }

        $data = $request->request->get('photos');

        $dm->persist($photo);
        $dm->flush();
        $photo->convertBase64AndRemove();
        $dm->flush();

        return $this->redirect($this->generateUrl('listing', array('photo' => $photo->getId(), 'coordinates' => $photo->getLocalisation())));
   }

   /**
    * @Route("/listing", name="listing")
    */
   public function listingAction(Request $request)
   {
		$dm = $this->get('doctrine_mongodb')->getManager();

		$coordinates = $request->get('coordinates', null);
		$photoid = $request->get('photo', null);
		$address = null;
		$elevators = array();

		if ($photoid && !$coordinates) {
			if ($photo = $dm->getRepository('AppBundle:Photo')->findOneById($photoid)) {
				if ($localisation = $photo->getLocalisation()) {
					$coordinates = $localisation->getCoordinatesLibelle();
				}
			}
       	}

       	if ($coordinates) {
            $coordinates = urldecode($coordinates);
       		$address = AdresseDataGouvApi::getAddrByCoordinates($coordinates);
       		$elevators = $dm->getRepository('AppBundle:Ascenseur')->findByCoordinates($coordinates);
       	}

       	return $this->render('default/listing.html.twig', array('coordinates' => $coordinates, 'address' => $address, 'photoid' => $photoid, 'elevators' => $elevators));
   }


   /**
    * @Route("/signalement", name="signalement")
    */
   public function signalementAction(Request $request)
   {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $coordinates = $request->get('coordinates', null);
        $coordinatesArr = null;
        if($coordinates) $coordinatesArr = explode(",",urldecode($coordinates));
        $ascenseur = new Ascenseur();
        if($request->get('ascenseur')) {
           $ascenseur = $dm->getRepository('AppBundle:Ascenseur')->find($request->get('ascenseur'));
        }
        if($coordinatesArr && count($coordinatesArr)==2){
            $ascenseur->setLatLon($coordinatesArr[1], $coordinatesArr[0]);
        }
        $signalement = new Signalement($ascenseur);
        if($request->get('photo')) {
           $photo = $dm->getRepository('AppBundle:Photo')->find($request->get('photo'));
           $photo->setAscenseur($signalement->getAscenseur());
           $signalement->getAscenseur()->addPhoto($photo);
        }
        $form = $this->createForm(SignalementType::class, $signalement, array('method' => Request::METHOD_POST, 'action' => $this->generateUrl('signalement', array('photo' => $request->get('photo'),"coordinates" => $coordinates))));

        if($request->getMethod() != Request::METHOD_POST) {

            return $this->render('default/signalement.html.twig', array("form" => $form->createView()));
        }

        $form->handleRequest($request);

        if(!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('default/signalement.html.twig', array("form" => $form->createView()));
        }

        $signalement->createEvenement();

        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($signalement->getAscenseur());
        $dm->persist($signalement);

        $dm->flush();

        return $this->redirect($this->generateUrl('ascenseur', array('id' => $signalement->getAscenseur()->getId())));
   }

   /**
    * @Route("/ascenseur/{id}", name="ascenseur")
    */
   public function ascenseurAction(Request $request, $id)
   {
       $dm = $this->get('doctrine_mongodb')->getManager();
       $ascenseur = $dm->getRepository('AppBundle:Ascenseur')->find($id);

       return $this->render('default/ascenseur.html.twig',array("ascenseur" => $ascenseur,"geojson" => $this->buildGeoJson($ascenseur)));
   }

   /**
    * @Route("/ascenseur/{ascenseur}/edition", name="ascenseur_edition")
    */
   public function ascenseurEditionAction(Request $request, $ascenseur)
   {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $ascenseur = $dm->getRepository('AppBundle:Ascenseur')->find($ascenseur);

        $form = $this->createForm(AscenseurType::class, $ascenseur, array('method' => Request::METHOD_POST));

        if($request->getMethod() != Request::METHOD_POST) {

           return $this->render('default/ascenseur_edition.html.twig', array("form" => $form->createView(), 'ascenseur' => $ascenseur));
        }

       $form->handleRequest($request);

       if(!$form->isSubmitted() || !$form->isValid()) {

           return $this->render('default/ascenseur_edition.html.twig', array("form" => $form->createView(), 'ascenseur' => $ascenseur));
       }

       $dm->flush();

       return $this->redirect($this->generateUrl('ascenseur', array('id' => $ascenseur->getId())));
   }

   private function buildGeoJson($ascenseur) {
        $geojson = new \stdClass();
        $geojson->type = "FeatureCollection";
        $geojson->features = array();

        $feature = new \stdClass();
        $feature->type = "Feature";
        $feature->properties = new \stdClass();
        $feature->properties->_id = $ascenseur->getId();
        $feature->properties->icon = 'ascenseur';

        $feature->geometry = new \stdClass();
        $feature->geometry->type = "Point";
        $feature->geometry->coordinates = array($ascenseur->getLon(), $ascenseur->getLat());

        $geojson->features[] = $feature;
        return $geojson;
    }


}
