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
    protected $twitter_profile_image_url;
    protected $createdAt;
    protected $updatedAt;
    protected $twitters;
    
    protected $fullName;
   	protected $dni;
   	protected $edad;
   	protected $telefono;
   	protected $provincia;
   	protected $mail;
   	protected $acceptNewsletter;
   	
   	protected $profilePicture;
    
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

    public function setTwitterProfileImageUrl($twitterProfileImageUrl)
    {
    	$this->twitter_profile_image_url = $twitterProfileImageUrl;
    
    	return $this;
    }
    
    public function getTwitterProfileImageUrl()
    {
    	return $this->twitter_profile_image_url;
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
    
    public function getCanonicalName()
    {
    	return $this->getFullName()?$this->getFullName():$this->getUsername();
    }
    
    public function isRegistered()
    {
    	return $this->getDni()?true:false;
    }
	
	public function getFullName() {
		return $this->fullName;
	}
	
	public function setFullName($fullName) {
		$this->fullName = $fullName;
		return $this;
	}
	public function getDni() {
		return $this->dni;
	}
	public function setDni($dni) {
		$this->dni = $dni;
		return $this;
	}
	public function getEdad() {
		return $this->edad;
	}
	public function setEdad($edad) {
		$this->edad = $edad;
		return $this;
	}
	public function getTelefono() {
		return $this->telefono;
	}
	public function setTelefono($telefono) {
		$this->telefono = $telefono;
		return $this;
	}
	public function getProvincia() {
		return $this->provincia;
	}
	public function setProvincia($provincia) {
		$this->provincia = $provincia;
		return $this;
	}
	public function getMail() {
		return $this->mail;
	}
	public function setMail($mail) {
		$this->mail = $mail;
		return $this;
	}	
	public function getAcceptNewsletter() {
		return $this->acceptNewsletter;
	}
	public function setAcceptNewsletter($acceptNewsletter) {
		$this->acceptNewsletter = $acceptNewsletter;
		return $this;
	}
}