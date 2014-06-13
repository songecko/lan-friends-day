<?php

namespace Odiseo\LanBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('OdiseoLanBundle:Default:index.html.twig', array('name' => $name));
    }
}
