<?php

namespace Odiseo\LanBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;
use Odiseo\LanBundle\Entity\User;

class LoadUserData extends DataFixture
{
    public function load(ObjectManager $manager)
    {
    	/** USERS **/
    	$userAdmin = new User();
    	$userAdmin->setUsername('admin');
    	$userAdmin->setEmail('admin@lan.com');
    	$userAdmin->setPlainPassword('123456');
    	$userAdmin->setEnabled(true);
    	$userAdmin->setRoles(array('ROLE_ADMIN'));
    	$manager->persist($userAdmin);
    	
    	$userTwitter = new User();
    	$userTwitter->setUsername('user');
    	$userTwitter->setEmail('user@lan.com');
    	$userTwitter->setPlainPassword('123456');
    	$userTwitter->setEnabled(true);
    	$userTwitter->setRoles(array('ROLE_USER'));
    	$manager->persist($userTwitter);
    	
    	$manager->flush();
    }
    
    public function getOrder()
    {
    	return 1;
    }
}
