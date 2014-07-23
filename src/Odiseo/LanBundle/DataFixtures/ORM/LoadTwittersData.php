<?php

namespace Odiseo\LanBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Odiseo\LanBundle\Entity\User;
use Odiseo\LanBundle\Entity\TwitterUser;

class LoadTwittersData extends DataFixture
{	
    public function load(ObjectManager $manager)
    {
    	/** USERS **/
    	/*$user = $manager->getRepository('OdiseoLanBundle:User')->findOneByUsername('user');
    	
    	/** TWITTERS **/
    	/*$twitter = new TwitterUser();
    	$twitter->setUser($user);
    	$twitter->setTwitter($this->faker->text);
    	$manager->persist($twitter);

        $manager->flush();*/
    }
    
    public function getOrder()
    {
    	return 2;
    }
}
