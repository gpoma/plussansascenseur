<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Behat\Transliterator\Transliterator;

/**
 * @MongoDB\Document
 * @MongoDB\Document(repositoryClass="AppBundle\Repository\AscenseurRepository")
 */
class Ascenseur {

    /**
      * @MongoDB\Id(strategy="AUTO")
      */
     protected $id;

     /**
     *  @MongoDB\ReferenceMany(targetDocument="Photo", mappedBy="ascenseur")
     */
    protected $photos;


    public function __construct()
    {
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
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
}
