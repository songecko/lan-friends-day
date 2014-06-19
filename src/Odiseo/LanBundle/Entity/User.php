<?php

namespace Odiseo\LanBundle\Entity;

use DateTime;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 */
class User extends BaseUser
{
    protected $id;
    protected $twitter_id;
    protected $twitter_access_token;
    protected $createdAt;
    protected $updatedAt;
    protected $twitters;
    
    public function __construct()
    {
    	parent::__construct();
    	$this->createdAt = new DateTime('now');
    }
    
    public function setTwitterId($twitterId)
    {
    	$this->twitter_id = $twitterId;
    
    	return $this;
    }
    
    public function getTwitterId()
    {
    	return $this->twitter_id;
    }
    
    public function setTwitterAccessToken($twitterAccessToken)
    {
    	$this->twitter_access_token = $twitterAccessToken;
    
    	return $this;
    }
    
    public function getTwitterAccessToken()
    {
    	return $this->twitter_access_token;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function addTwitter(\Odiseo\LanBundle\Entity\TwitterUser $twitters)
    {
        $this->twitters[] = $twitters;

        return $this;
    }

    public function removeTwitter(\Odiseo\LanBundle\Entity\TwitterUser $twitters)
    {
        $this->twitters->removeElement($twitters);
    }

    public function getTwitters()
    {
        return $this->twitters;
    }
    
    public function __toString()
    {
    	return $this->getUsername();
    }
}
