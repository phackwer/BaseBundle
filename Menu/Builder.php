<?php

namespace SanSIS\Core\BaseBundle\Menu;

use Knp\Menu\Silex\RouterAwareFactory;

class Builder
{

    public function menuExemplo(RouterAwareFactory $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Início', array(
            'route' => 'sansis_core_base_homepage',
            'routeAbsolute' => true
        ));
        
        $about = $menu->addChild('Sobre o sistema', array(
            'uri' => '#aboutDialog',
            'data-toggle' => 'modal'
        ));
        $about->setLinkAttribute('data-toggle', 'modal');

        return $menu;
    }

}
