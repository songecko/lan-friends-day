<?php
namespace Odiseo\LanBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

class BackendMenuBuilder
{
    private $factory;
    protected $securityContext;
    
    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, SecurityContextInterface $securityContext)
    {
        $this->factory = $factory;
        $this->securityContext = $securityContext;
    }

    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'sidebar-menu'
            )
        ));

        $menu->addChild('dashboard', array(
        		'route' => 'odiseo_lan_backend_dashboard',
        		'labelAttributes' => array('icon' => 'fa-dashboard'),
        ))->setLabel("Dashboard");
        
        $menu->addChild('user', array(
        		'route' => 'odiseo_lan_user_index',
        		'labelAttributes' => array('icon' => 'fa-user'),
        ))->setLabel("Usuarios");
        
        $menu->addChild('twitteruser', array(
        		'route' => 'odiseo_lan_twitteruser_index',
        		'labelAttributes' => array('icon' => 'fa-twitter'),
        ))->setLabel("Tweets");
        
        $menu->addChild('configuration', array(
        		'route' => 'odiseo_lan_configuration_index',
        		'labelAttributes' => array('icon' => 'fa-wrench'),
        ))->setLabel("Configuracion");
        
        return $menu;
    }
}