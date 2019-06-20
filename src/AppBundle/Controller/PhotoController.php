<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Document\Photo;
use AppBundle\Type\PhotoType;

class PhotoController extends Controller
{
    /**
     * On upload une photo depuis la page d'accueil
     *
     * @Route("/photo/upload", name="photo_upload")
     *
     * @param Request $request La requête
     * @return Response La réponse
     */
    public function photoUploadAction(Request $request)
    {
        if (! $request->isMethod(Request::METHOD_POST)) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $dm = $this->get('doctrine_mongodb')->getManager();
        $photo = new Photo();

        $uploadPhotoForm = $this->createForm(PhotoType::class, $photo, [
            'method' => 'POST'
        ]);

        $uploadPhotoForm->handleRequest($request);

        if(! $uploadPhotoForm->isValid()) {
            $uploadPhotoForm = $uploadPhotoForm->createView();
            return $this->render('default/index.html.twig', compact('uploadPhotoForm'));
        }

        $data = $request->request->get('photos');

        $dm->persist($photo);
        $photo->operate();
        $dm->flush();

        return $this->redirect($this->generateUrl('localisation', [
            'photo' => $photo->getId(),
            'coordinates' => $photo->getLocalisation()
        ]));
    }

    /**
     * On affiche la photo
     *
     * @Route("/photo/{id}", name="photo", requirements={"id"="\w{24}"})
     *
     * @param Request $request La requête
     * @param string $id L'id de la Photo
     * @return Response La réponse
     */
    public function photoAction(Request $request, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $photo = $dm->getRepository(Photo::class)->find($id);

        $response = new Response();

        if($photo->getImageSize()) {
            $response->headers->set('Content-Length', $photo->getImageSize());
        }

        $response->headers->set('Content-Type', ($photo->getExt()) ? $photo->getExt() : "image");

        $response->setContent(base64_decode($photo->getBase64()));

        return $response;
    }
}
