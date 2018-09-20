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

        return $this->render('default/index.html.twig',array("uploadPhotoForm" => $uploadPhotoForm->createView(),"photos" => $photos));
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
    * @Route("/signalement/{ascenseurid}", name="signalement")
    */
   public function singalementAction(Request $request,$ascenseurid)
   {
       $dm = $this->get('doctrine_mongodb')->getManager();
       $ascenseur = $dm->getRepository('AppBundle:Ascenseur')->findOneById($ascenseurid);
       // signalement = new Signalement();
       return $this->render('default/signalement.html.twig',array("ascenseur" => $ascenseur));
   }


}
