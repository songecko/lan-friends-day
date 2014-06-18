<?php

namespace Odiseo\LanBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function dashboardAction()
    {
        return $this->render('OdiseoLanBundle:Backend/Main:dashboard.html.twig');
    }
}
