<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/** 
 * @MongoDB\EmbeddedDocument 
 * @MongoDB\Index(keys={"coordinates"="2d"})
 * */
class GeoJson {

    /**
      * @MongoDB\Field(type="string")
      */
     protected $type;

     /** @MongoDB\EmbedOne(targetDocument="Coordinates") */
    public $coordinates;

    public function __construct() {
        $this->setCoordinates(new Coordinates());
    }

    /**
     * Set type
     *
     * @param string $type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set coordinates
     *
     * @param AppBundle\Document\Coordinates $coordinates
     * @return self
     */
    public function setCoordinates(\AppBundle\Document\Coordinates $coordinates)
    {
        $this->coordinates = $coordinates;
        return $this;
    }

    /**
     * Get coordinates
     *
     * @return AppBundle\Document\Coordinates $coordinates
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    public function getCoordinatesLibelle()
    {
    	return $this->getCoordinates()->getLibelle();
    }

}
