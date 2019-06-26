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
    
    public static $connaissanceList = array(
        "FACEBOOK" => "via Facebook",
        "INSTAGRAM" => "via Instagram",
        "AMIS" => "par des amis",
        "SOIREE_LANCEMENT" => "suite à la soirée du lancement de la démarche nationale du 22 mars dernier",
        "MOBILISATIONS" => "en participant à une ou plusieurs mobilisations",
        "MEDIAS" => "par les médias (télé, journaux, ...)"
    );
    
    public static $complementsList = array(
        "HANDICAP" => "Personne avec un handicap moteur",
        "MEDICAL" => "Personne avec un suivi médical lourd",
        "FAMILLE" => "Famille avec enfants en bas âge",
        "TRAVAIL_DOMICILE" => "Vous travaillez à domicile (assistante maternelle, ...)"
    );

    /**
      * @MongoDB\Id(strategy="AUTO")
      */
    protected $id;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Ascenseur", inversedBy="signalements")
     *
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

    /**
     * @MongoDB\Field(type="boolean")
     *
     */
    protected $intervention;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $nom;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $prenom;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $codeInterphone;

    /**
     * @MongoDB\Field(type="boolean")
     *
     */
    protected $proprietaire;

    /**
     * @MongoDB\Field(type="collection")
     */
    protected $complements;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $connaissance;
    


    public function setNom($nom) {
        $this->nom = $nom;
        return $this;
    }
    
    public function getNom() {
        return $this->nom;
    }
    
    public function setPrenom($prenom) {
        $this->prenom = $prenom;
        return $this;
    }
    
    public function getPrenom() {
        return $this->prenom;
    }
    
    public function setCodeInterphone($codeInterphone) {
        $this->codeInterphone = $codeInterphone;
        return $this;
    }
    
    public function getCodeInterphone() {
        return $this->codeInterphone;
    }
    
    public function setProprietaire($proprietaire) {
        $this->proprietaire = $proprietaire;
        return $this;
    }
    
    public function getProprietaire() {
        return $this->proprietaire;
    }
    
    public function setComplements($complements) {
        $this->complements = $complements;
        return $this;
    }
    
    public function getComplements() {
        return $this->complements;
    }
    
    public function setConnaissance($connaissance) {
        $this->connaissance = $connaissance;
        return $this;
    }
    
    public function getConnaissance() {
        return $this->connaissance;
    }

    public function __construct(Ascenseur $ascenseur) {
        $this->abonnement = false;
        $this->intervention = false;
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

    public function getIntervention() {
    
        return $this->intervention;
    }
    
    public function setIntervention($intervention) {
        $this->intervention = $intervention;
    
        return $this;
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

    protected function createEvenement($description, $commentaire = true) {
      if($this->getCommentaire() && $commentaire) {
          $description .= " (".$this->getCommentaire().")";
      }
      $this->getAscenseur()->addEvenement($this->getDate(), $description, $this->getPseudo());
    }

    public function createEnPanne() {
        $this->createFollower();
        $this->createEvenement("Ascenseur signalé en panne");
    }

    public function createFollower() {
        $this->createEvenement("Une nouvelle personne a rejoint la communauté", false);
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
