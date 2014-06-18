<?php

namespace Odiseo\LanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Configuration
 */
class Configuration
{
    private $id;
    private $isAvailable;
    
    public function getId()
    {
        return $this->id;
    }

    public function setIsAvailable($isAvailable)
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function getIsAvailable()
    {
        return $this->isAvailable;
    }
}
