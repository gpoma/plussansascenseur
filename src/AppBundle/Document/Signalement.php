<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @MongoDB\Document(repositoryClass="AppBundle\Repository\AscenseurRepository")
 */
class Signalement {

    /**
      * @MongoDB\Id(strategy="AUTO")
      */
     protected $id;

}
