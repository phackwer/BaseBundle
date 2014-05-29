<?php

namespace SanSIS\Core\BaseBundle\Menu;

use Knp\Menu\Silex\RouterAwareFactory;

class Builder
{

    public function menuExemplo(RouterAwareFactory $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Início', array(
            'route' => 'SanSIS_core_base_homepage',
            'routeAbsolute' => true
        ));

        return $menu;
    }

}
