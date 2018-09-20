<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @MongoDB\Document(repositoryClass="AppBundle\Repository\AscenseurRepository")
 */
class Ascenseur {

    const STATUT_ENPANNE = "ENPANNE";

    /**
      * @MongoDB\Id(strategy="AUTO")
      */
     protected $id;


     /** @MongoDB\EmbedOne(targetDocument="GeoJson") */
    public $localisation;

     /**
     *  @MongoDB\ReferenceMany(targetDocument="Photo", mappedBy="ascenseur")
     */
    protected $photos;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $statut;

    /**
     *  @MongoDB\EmbedMany(targetDocument="Evennement")
     *
     */
    protected $historique;


    public function __construct()
    {
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setStatut(self::STATUT_ENPANNE);
    }

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add photo
     *
     * @param AppBundle\Document\Photo $photo
     */
    public function addPhoto(\AppBundle\Document\Photo $photo)
    {
        $this->photos[] = $photo;
    }

    /**
     * Remove photo
     *
     * @param AppBundle\Document\Photo $photo
     */
    public function removePhoto(\AppBundle\Document\Photo $photo)
    {
        $this->photos->removeElement($photo);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection $photos
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set localisation
     *
     * @param AppBundle\Document\GeoJson $localisation
     * @return self
     */
    public function setLocalisation(\AppBundle\Document\GeoJson $localisation)
    {
        $this->localisation = $localisation;
        return $this;
    }

    /**
     * Get localisation
     *
     * @return AppBundle\Document\GeoJson $localisation
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }

    public function setLatLon($lat,$lon){

        $localisation = new GeoJson();
        $localisation->setType("Point");

        $coordinates = new Coordinates();
        $coordinates->setX($lon);
        $coordinates->setY($lat);
        $localisation->setCoordinates($coordinates);

        $this->setLocalisation($localisation);
    }

    public function getLon(){
        return $this->getLocalisation()->getCoordinates()->getX();
    }
    public function getLat(){
        return $this->getLocalisation()->getCoordinates()->getY();
    }

    /**
     * Set statut
     *
     * @param string $statut
     * @return $this
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
        return $this;
    }

    /**
     * Get statut
     *
     * @return string $statut
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Add historique
     *
     * @param AppBundle\Document\Evennement $historique
     */
    public function addHistorique(\AppBundle\Document\Evennement $historique)
    {
        $this->historique[] = $historique;
    }

    /**
     * Remove historique
     *
     * @param AppBundle\Document\Evennement $historique
     */
    public function removeHistorique(\AppBundle\Document\Evennement $historique)
    {
        $this->historique->removeElement($historique);
    }

    /**
     * Get historique
     *
     * @return \Doctrine\Common\Collections\Collection $historique
     */
    public function getHistorique()
    {
        return $this->historique;
    }
}
