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
    	$configuration->setDateBegin(new \DateTime('now + 3 days'));
    	$configuration->setDateEnd(new \DateTime('now + 5 days'));
    	$manager->persist($configuration);
    	
    	$manager->flush();
    }
    
    public function getOrder()
    {
    	return 3;
    }
}
