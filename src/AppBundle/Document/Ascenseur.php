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


}
