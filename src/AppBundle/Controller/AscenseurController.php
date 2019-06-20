<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

use AppBundle\Document\Ascenseur;
use AppBundle\Document\Signalement;

use AppBundle\Type\AscenseurType;
use AppBundle\Type\FollowerType;

class AscenseurController extends Controller
{
    /** @var int $perpage Le nombre par page */
    const PERPAGE = 5;

    /**
     * On liste les derniers ascenseurs mis à jours
     *
     * @Route("/listing", name="listing")
     *
     * @param Request $request La requête
     * @return Response La réponse
     */
    public function listingAction(Request $request)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $limit = (int) $request->query->get('perpage', self::PERPAGE);
        $page = (int) $request->query->get('page', 1);

        $validator = new Assert\Type('int');

        $errors = $this->get('validator')->validate(
            $page,
            $validator
        );

        if (0 !== count($errors) || $page < 1) {
            $page = 1;
        }

        $skip = ($page - 1) * $limit;

        $ascenseurs = $dm->getRepository(Ascenseur::class)
                         ->paginate($limit, $skip);

        $pages = ceil($dm->getRepository(Ascenseur::class)->count() / $limit);

        $query_string = ($limit === self::PERPAGE) ? '' : "perpage=$limit&";

        return $this->render('default/listing.html.twig', compact(
            'ascenseurs', 'page', 'pages', 'query_string'
        ));
    }

    /**
     * On affiche les informations d'un ascenseur
     *
     * @Route("/ascenseur/{id}", name="ascenseur", requirements={"id"="\w{24}"})
     *
     * @param Request $request La requête
     * @param string $id L'id de l'ascenseur
     * @return Response La réponse
     */
    public function ascenseurAction(Request $request, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $ascenseur = $dm->getRepository(Ascenseur::class)->find($id);

        $geojson = $this->buildGeoJson($ascenseur);

        return $this->render('default/ascenseur.html.twig', compact(
            'ascenseur', 'geojson'
        ));
    }

    /**
     * On édite les informations de l'ascenseur
     * GET: On affiche le formulaire
     * POST: On enregistre les informations
     *
     * @Route("/ascenseur/{id}/edition", name="ascenseur_edition", requirements={"id"="\w{24}"})
     *
     * @param Request $request La requête
     * @param string $id L'id de l'ascenseur
     * @return Response La réponse
     */
    public function ascenseurEditionAction(Request $request, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $ascenseur = $dm->getRepository(Ascenseur::class)->find($id);

        $form = $this->createForm(AscenseurType::class, $ascenseur, [
            'method' => Request::METHOD_POST
        ]);

        if(! $request->isMethod(Request::METHOD_POST)) {
            $form = $form->createView();
            return $this->render('default/ascenseur_edition.html.twig', compact('form', 'ascenseur'));
        }

        $form->handleRequest($request);

        if(! $form->isSubmitted() || ! $form->isValid()) {
            $form = $form->createView();
            return $this->render('default/ascenseur_edition.html.twig', compact('form', 'ascenseur'));
        }

        $dm->flush();

        $dm->getRepository(Ascenseur::class)
           ->saveVersion($ascenseur, new \DateTime(), "Des informations sur l'ascenseur ont été complétées", null);

        return $this->redirect($this->generateUrl('ascenseur', ['id' => $ascenseur->getId()]));
    }

    /**
     * Un nouveau follower suit l'ascenseur
     * GET: On affiche un formulaire d'informations à remplir
     * POST: On enregistre les infos
     *
     * @Route("/ascenseur/{id}/join", name="ascenseur_follower", requirements={"id"="\w{24}"})
     */
    public function followerAction(Request $request, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $ascenseur = $dm->getRepository(Ascenseur::class)->find($id);
        $signalement = new Signalement($ascenseur);

        $form = $this->createForm(FollowerType::class, $signalement, [
            'method' => Request::METHOD_POST
        ]);

        if(! $request->isMethod(Request::METHOD_POST)) {
            $form = $form->createView();
            return $this->render('default/follower.html.twig', compact('form', 'ascenseur'));
        }

        $form->handleRequest($request);

        if(! $form->isSubmitted() || ! $form->isValid()) {
            $form = $form->createView();
            return $this->render('default/follower.html.twig', compact('form', 'ascenseur'));
        }

        if ($signalement->getPseudo() || $signalement->getEmail() || $signalement->getTelephone()) {
            $signalement->createFollower();
            $dm->persist($signalement);

            $dm->flush();
        }

        return $this->redirect($this->generateUrl('ascenseur', ['id' => $ascenseur->getId()]));

    }

    /**
     * Retourne les informations de localisation de l'ascenseur
     *
     * @param Ascenseur $ascenseur L'ascenseur
     * @return stdClass Les données geojson
     */
    private function buildGeoJson(Ascenseur $ascenseur)
    {
        $geojson = new \stdClass();
        $geojson->type = "FeatureCollection";
        $geojson->features = [];

        $feature = new \stdClass();
        $feature->type = "Feature";
        $feature->properties = new \stdClass();
        $feature->properties->_id = $ascenseur->getId();
        $feature->properties->icon = 'ascenseur';

        $feature->geometry = new \stdClass();
        $feature->geometry->type = "Point";
        $feature->geometry->coordinates = [$ascenseur->getLon(), $ascenseur->getLat()];

        $geojson->features[] = $feature;
        return $geojson;
    }
}
