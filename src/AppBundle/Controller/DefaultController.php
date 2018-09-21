<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Document\Photo;
use AppBundle\Type\PhotoType;
use AppBundle\Document\Ascenseur;

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
       $dm = $this->get('doctrine_mongodb')->getManager();
       $photo = new Photo();
       $uploadPhotoForm = $this->createForm(PhotoType::class, $photo, array(
           'action' => $this->generateUrl('photo_upload'),
           'method' => 'POST',
       ));
       if ($request->isMethod('POST')) {
           $uploadPhotoForm->handleRequest($request);
           if($uploadPhotoForm->isValid()){
           $data = $request->request->get('photo');
           $lat = floatval($data['lat']);
           $lon = floatval($data['lon']);
             $f = $uploadPhotoForm->getData()->getImageFile();
             if($f){
                 $dm->persist($photo);
                 $dm->flush();
                 $photo->convertBase64AndRemove();
                 $dm->flush();
             }
             $ascenseur = new Ascenseur();
             $ascenseur->setLatLon($lat,$lon);
             $ascenseur->addPhoto($photo);
             $dm->persist($ascenseur);
             $dm->flush();
         }else{
             var_dump("not valid"); exit;
         }
           $urlRetour = $this->generateUrl('signalement',array('ascenseurid' => $ascenseur->getId()));
           return $this->redirect($urlRetour);
       }
   }

   /**
    * @Route("/listing", name="listing")
    */
   public function listingAction(Request $request, $photoid = null)
   {
		$dm = $this->get('doctrine_mongodb')->getManager();
		
		$coordinates = $request->get('coordinates', null);
		$photoid = $request->get('photoid', null);
		
		if ($photoid && !$coordinates) {
			if ($photo = $dm->getRepository('AppBundle:Photo')->findOneById($photoid)) {
				
			} else {
				$photoid = null;
			}
       	}

       	return $this->render('default/listing.html.twig',array());
   }

   /**
    * @Route("/signalement/{ascenseurid}", name="signalement")
    */
   public function singalementAction(Request $request,$ascenseurid)
   {
       $dm = $this->get('doctrine_mongodb')->getManager();
       $ascenseur = $dm->getRepository('AppBundle:Ascenseur')->findOneById($ascenseurid);
       // signalement = new Signalement();
       return $this->render('default/signalement.html.twig',array("ascenseur" => $ascenseur));
   }

   /**
    * @Route("/ascenseur/{ascenseurid}", name="ascenseur")
    */
   public function ascenseurAction(Request $request,$ascenseurid)
   {
       $dm = $this->get('doctrine_mongodb')->getManager();
       $ascenseur = $dm->getRepository('AppBundle:Ascenseur')->findOneById($ascenseurid);
       return $this->render('default/ascenseur.html.twig',array("ascenseur" => $ascenseur,"geojson" => $this->buildGeoJson($ascenseur)));
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
