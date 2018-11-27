<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use AppBundle\Document\Ascenseur;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
use Doctrine\ODM\MongoDB\Mapping\Annotations\PreUpdate;
use Doctrine\ODM\MongoDB\Mapping\Annotations\PrePersist;

/**
 * @MongoDB\Document
 * @MongoDB\Document(repositoryClass="AppBundle\Repository\SignalementRepository") @HasLifecycleCallbacks
 */
class Signalement {

    public static $usageList = array(
        "HABITANT" => "J'habite ici",
        "TRAVAIL" => "Je travaille ici",
        "VISITEUR" => "Je rend visite à quelqu'un",
        "MEDICAL" => "J'apporte une aide médicale",
        "PROFESSIONNEL" => "Je livre ou j'interviens chez quelqu'un",
        "AUTRE" => "Autre"
    );

    /**
      * @MongoDB\Id(strategy="AUTO")
      */
    protected $id;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Ascenseur")
     */
    private $ascenseur;

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

    /**
     * @MongoDB\Field(type="date")
     *
     */
    protected $updatedAt;

    public function __construct(Ascenseur $ascenseur) {
        $this->abonnement = false;
        $this->date = new \DateTime();
        $this->datePanne = new \DateTime();
        $this->ascenseur = $ascenseur;
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
     * Get ascenseur
     *
     * @return AppBundle\Document\Ascenseur $ascenseur
     */
    public function getAscenseur()
    {
        return $this->ascenseur;
    }

    public function createEvenement() {
        $this->getAscenseur()->addEvenement($this->getDate(), "Signalé en panne (".$this->getCommentaire().")", $this->getPseudo());
    }

    /**
     * Get updatedAt
     *
     * @return date $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @MongoDB\PrePersist()
     * @MongoDB\PreUpdate()
     */
    public function preSave() {
        $this->updatedAt = new \DateTime();
    }

}
