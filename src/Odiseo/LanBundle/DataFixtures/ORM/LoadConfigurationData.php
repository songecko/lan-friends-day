<?php

namespace Odiseo\LanBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;
use Odiseo\LanBundle\Entity\Configuration;

class LoadConfigurationData extends DataFixture
{
    public function load(ObjectManager $manager)
    {
    	/** CONFIGURATION **/
    	$configuration = new Configuration();
    	$configuration->setIsAvailable(false);
    	$manager->persist($configuration);
    	
    	$manager->flush();
    }
    
    public function getOrder()
    {
    	return 3;
    }
}
