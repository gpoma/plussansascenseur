<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Document\Photo;
use AppBundle\Type\PhotoType;
use AppBundle\Type\SignalementType;
use AppBundle\Type\FollowerType;
use AppBundle\Type\AscenseurType;
use AppBundle\Document\Signalement;
use AppBundle\Document\Ascenseur;
use AppBundle\Lib\AdresseDataGouvApi;
use AppBundle\Repository\AscenseurRepository;
use Symfony\Component\HttpFoundation\Response;

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
        $photo->operate();
        $dm->flush();

        return $this->redirect($this->generateUrl('localisation', array('photo' => $photo->getId(), 'coordinates' => $photo->getLocalisation())));
   }


   /**
    * @Route("/localisation", name="localisation")
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
     return $this->render('default/localisation.html.twig', array('coordinates' => $coordinates, 'photoid' => $photoid, 'address' => $address));
   }

   /**
    * @Route("/nearby", name="nearby")
    */
   public function nearbyAction(Request $request)
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

       	return $this->render('default/nearby.html.twig', array('coordinates' => $coordinates, 'address' => $address, 'photoid' => $photoid, 'elevators' => $elevators));
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
           $signalement->getAscenseur()->addPhoto($photo);
        }
        $form = $this->createForm(SignalementType::class, $signalement, array('method' => Request::METHOD_POST));

        if($request->getMethod() != Request::METHOD_POST) {

            return $this->render('default/signalement.html.twig', array("form" => $form->createView()));
        }

        $form->handleRequest($request);

        if(!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('default/signalement.html.twig', array("form" => $form->createView()));
        }

        $dm->persist($signalement->getAscenseur());
        $dm->flush();

        $dm->getRepository('AppBundle:Ascenseur')->saveVersion($signalement->getAscenseur(), new \DateTime(), "Création de l'ascenseur", $signalement->getPseudo());

        $signalement->createEnPanne();
        $dm->persist($signalement);
        $dm->flush();

        return $this->redirect($this->generateUrl('ascenseur', array('id' => $signalement->getAscenseur()->getId())));
   }

   /**
    * @Route("/photo/{id}", name="photo")
    */
   public function photoAction(Request $request, $id)
   {
       $dm = $this->get('doctrine_mongodb')->getManager();
       $photo = $dm->getRepository('AppBundle:Photo')->find($id);

       $response = new Response();

        if($photo->getImageSize()) {
           $response->headers->set('Content-Length', $photo->getImageSize());
        }
       $response->headers->set('Content-Type', ($photo->getExt()) ? $photo->getExt() : "image");

       $response->setContent(base64_decode($photo->getBase64()));



       return $response;
   }
}
