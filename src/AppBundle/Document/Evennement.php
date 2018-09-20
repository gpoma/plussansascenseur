<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 * AppBundle\Document\Evennement
 */
class Evennement
{
    /**
     * @MongoDB\Field(type="date")
     *
     */
    protected $date;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $auteur;

    /**
     * @MongoDB\Field(type="string")
     *
     */
    protected $commentaire;

    /**
     * Set date
     *
     * @param date $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return date $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set auteur
     *
     * @param string $auteur
     * @return $this
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;
        return $this;
    }

    /**
     * Get auteur
     *
     * @return string $auteur
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return $this
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string $commentaire
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }
}
