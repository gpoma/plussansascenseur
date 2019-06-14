<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 *
 * @MongoDB\EmbeddedDocument
 **/
class Exif
{
    const ROT_180 = 3;
    const ROT_M90 = 6;
    const ROT_90 = 8;

    /** @MongoDB\Field(type="int") */
    protected $rotation;

    /** var array $exif L'exif de la photo */
    protected $exif = [];

    public function __construct(array $exif)
    {
        $this->exif = $exif;
        $this->rotation = (isset($this->exif['Orientation']))
            ? $this->exif['Orientation']
            : 0;
    }

    /**
     * Remplace l'Exif de la photo
     *
     * @param array $exif L'exif de la photo
     */
    public function setExif(array $exif)
    {
        $this->exif = $exif;
    }

    /**
     * On récupère les données Exif de la photo
     *
     * @return array Les données
     */
    public function getExif()
    {
        return $this->exif;
    }

    /**
     * Set rotation
     *
     * @param int $rotation La valeur de rotation de l'image
     */
    public function setRotation(int $rotation)
    {
        $this->rotation = $rotation;
    }

    /**
     * Get rotation
     *
     * @return int La rotation
     */
    public function getRotation()
    {
        return $this->rotation;
    }
}
