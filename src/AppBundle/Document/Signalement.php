<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @MongoDB\Document(repositoryClass="AppBundle\Repository\SignalementRepository")
 */
class Signalement {

    public static $usageList = array(
        "HABITANT" => "J'habite ici",
        "TRAVAIL" => "Je travaille ici",
        "VISITEUR" => "Je rend visite à quelqu'une",
        "PROFESSIONNEL" => "Je livre ou j'intervient chez quelqu'un",
    );

    /**
      * @MongoDB\Id(strategy="AUTO")
      */
    protected $id;

    /**
     * @MongoDB\Field(type="date")
     *
     */
    protected $date;

    /**
     * @MongoDB\Field(type="date")
     *
     */
    protected $datePanne;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $etage;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $usage;

    /**
     * @MongoDB\Field(type="boolean")
     *
     */
    protected $etageAtteint;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $duree;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $commentaire;

    /**
     * @MongoDB\Field(type="boolean")
     *
     */
    protected $abonnement;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $pseudo;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $email;

    /**
     * @MongoDB\Field(type="string")
     *string
     */
    protected $telephone;

    public function __construct() {

        $this->abonnement = false;
    }

    public function getId() {

        return $this->id;
    }

    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    public function getDate() {

        return $this->date;
    }

    public function setDatePanne($datePanne) {
        $this->datePanne = $datePanne;

        return $this;
    }

    public function getDatePanne() {

        return $this->datePanne;
    }

    public function setEtage($etage) {
        $this->etage = $etage;

        return $this;
    }

    public function getEtage() {

        return $this->etage;
    }

    public function setUsage($usage) {
        $this->usage = $usage;

        return $this;
    }

    public function getUsage() {

        return $this->usage;
    }

    public function setDuree($duree) {
        $this->duree = $duree;

        return $this;
    }

    public function getDuree() {

        return $this->duree;
    }

    public function setCommentaire($commentaire) {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getCommentaire() {

        return $this->commentaire;
    }

    public function getEtageAtteint() {

        return $this->etageAtteint;
    }

    public function setEtageAtteint($etageAtteint) {
        $this->etageAtteint = $etageAtteint;

        return $this;
    }

    public function getAbonnement() {

        return $this->abonnement;
    }

    public function setAbonnement($abonnement) {
        $this->abonnement = $abonnement;

        return $this;
    }

    public function setPseudo($pseudo) {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPseudo() {

        return $this->pseudo;
    }

    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    public function getEmail() {

        return $this->email;
    }

    public function setTelephone($telephone) {
        $this->telephone = $telephone;

        return $this;
    }

    public function getTelephone() {

        return $this->telephone;
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
}
