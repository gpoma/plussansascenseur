<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="AppBundle\Repository\AscenseurRepository")
 */
class Ascenseur {

    const STATUT_ENPANNE = "EN_PANNE";

    /**
      * @MongoDB\Id(strategy="AUTO")
      */
     protected $id;


     /** @MongoDB\EmbedOne(targetDocument="GeoJson") */
    protected $localisation;

     /**
     *  @MongoDB\ReferenceMany(targetDocument="Photo", mappedBy="ascenseur")
     */
    protected $photos;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $adresse;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $codePostal;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $commune;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $emplacement;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $reference;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $marque;

    /**
     * @MongoDB\Field(type="int")
     *
     */
    protected $etageMin;

    /**
     * @MongoDB\Field(type="int")
     *
     */
    protected $etageMax;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $telephoneDepannage;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $statut;

    /**
     *  @MongoDB\EmbedMany(targetDocument="Evenement")
     *
     */
    protected $historique;


    public function __construct()
    {
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setStatut(self::STATUT_ENPANNE);
        $this->localisation = new GeoJson();
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

    public function setAdresse($adresse){
        $this->adresse = $adresse;

        return $this;
    }

    public function getAdresse() {

        return $this->adresse;
    }

    public function setCodePostal($codePostal){
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getCodePostal() {

        return $this->codePostal;
    }

    public function setCommune($commune){
        $this->commune = $commune;

        return $this;
    }

    public function getCommune() {

        return $this->commune;
    }

    public function setEmplacement($emplacement){
        $this->emplacement = $emplacement;

        return $this;
    }

    public function getEmplacement() {

        return $this->emplacement;
    }

    public function setReference($reference){
        $this->reference = $reference;

        return $this;
    }

    public function getReference() {

        return $this->reference;
    }

    public function setMarque($marque){
        $this->marque = $marque;

        return $this;
    }

    public function getMarque() {

        return $this->marque;
    }

    public function setEtageMin($etageMin){
        $this->etageMin = $etageMin;

        return $this;
    }

    public function getEtageMin() {

        return $this->etageMin;
    }

    public function setEtageMax($etageMax){
        $this->etageMax = $etageMax;

        return $this;
    }

    public function getEtageMax() {

        return $this->etageMax;
    }

    public function setTelephoneDepannage($telephoneDepannage){
        $this->telephoneDepannage = $telephoneDepannage;

        return $this;
    }

    public function getTelephoneDepannage() {

        return $this->telephoneDepannage;
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
     * Get historique
     *
     * @return \Doctrine\Common\Collections\Collection $historique
     */
    public function getHistorique()
    {
        return $this->historique;
    }

    public function addEvenement($date, $infos, $auteur) {
        $evenement = new Evenement();
        $evenement->setDate($date);
        $evenement->setCommentaire($infos);
        $evenement->setAuteur($auteur);

        $this->historique[] = $evenement;
    }
}
