<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

use AppBundle\Document\Ascenseur;
use AppBundle\Document\Photo;

use AppBundle\Type\PhotoType;
use AppBundle\Type\AscenseurType;

use AppBundle\Repository\AscenseurRepository;

class AscenseurController extends Controller
{
    /** @var int $perpage Le nombre par page */
    const PERPAGE = 5;

    /**
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
