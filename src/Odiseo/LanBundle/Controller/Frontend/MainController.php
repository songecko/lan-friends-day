<?php

namespace Odiseo\LanBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->render('OdiseoLanBundle:Frontend/Main:index.html.twig');
    }
}
