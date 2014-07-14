<?php

namespace Odiseo\LanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Configuration
 */
class Configuration
{
    private $id;
    private $dateBegin;
    private $dateEnd;
    
    public function getId()
    {
        return $this->id;
    }

	public function setDateBegin($dateBegin)
    {
        $this->dateBegin = $dateBegin;
    
        return $this;
    }

    public function getDateBegin()
    {
        return $this->dateBegin;
    }

    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;
    
        return $this;
    }
    
    public function getDateEnd()
    {
        return $this->dateEnd;
    }
    
    public function isCampaignActive()
    {
    	return (
    		(strtotime("now") > $this->getDateBegin()->format('U')) &&
    		(strtotime("now") < $this->getDateEnd()->format('U'))
		);
    }
}
