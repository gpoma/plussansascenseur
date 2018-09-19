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
        $photos = $dm->getRepository('AppBundle:Photo')->findAll();

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
             $f = $uploadPhotoForm->getData()->getImageFile();
             if($f){
                 $dm->persist($photo);
                 $dm->flush();
                 $photo->convertBase64AndRemove();
                 $dm->flush();
             }
           }
           $urlRetour = $this->generateUrl('ascenseurs-liste');
           return $this->redirect($urlRetour);
       }
   }

   /**
    * @Route("/ascenseurs-liste", name="ascenseurs-liste")
    */
   public function ascenseursListeAction(Request $request)
   {
       $dm = $this->get('doctrine_mongodb')->getManager();
       $ascenseur = new Ascenseur();
       $dm->persist($ascenseur);
       $dm->flush();
       return $this->redirectToRoute('homepage');
   }

    /**
     * @Route("/creation-ascenseur", name="creation_ascenseur")
     */
    public function createAscenseurAction(Request $request)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $ascenseur = new Ascenseur();
        $dm->persist($ascenseur);
        $dm->flush();
        return $this->redirectToRoute('homepage');
    }
}
