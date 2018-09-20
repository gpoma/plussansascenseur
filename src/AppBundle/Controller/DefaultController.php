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
           $data = $request->request->get('photos');
           $lat = $data['lat'];
           $lon = $data['lon'];
             $f = $uploadPhotoForm->getData()->getImageFile();
             if($f){
                 $dm->persist($photo);
                 $dm->flush();
                 $photo->convertBase64AndRemove();
                 $photo->setLatLon($lat,$lon);
                 $dm->flush();
             }
               $urlRetour = $this->generateUrl('listing',array('id' => $photo->getId()));
               return $this->redirect($urlRetour);
            }
        }
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
