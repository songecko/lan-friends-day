<?php

namespace Odiseo\LanBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * TwitterUser
 */
class TwitterUser
{
    private $id;
    private $twitter;
    private $createdAt;
    private $updatedAt;
    private $user;
    
    public function __construct()
    {
    	$this->createdAt = new DateTime('now');
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getTwitter()
    {
        return $this->twitter;
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

    public function setUser(\Odiseo\LanBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }
}
