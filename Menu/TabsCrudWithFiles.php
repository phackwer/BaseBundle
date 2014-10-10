<?php

namespace SanSIS\Core\BaseBundle\Menu;

use Knp\Menu\Silex\RouterAwareFactory;

/**
 * 
 * @TODO - Criar uma classe para encapsular esta funcionalidade de tabs
 * 
 * @author pablo.sanchez
 *
 */
class TabsCrudWithFiles
{

    public function tabs(RouterAwareFactory $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $item = $menu->addChild('Informações', array(
            'uri' => '#tab1',
            'linkAttributes' => array(
                'id' => 'idT1',
                'data-toggle' => 'tab',
            )
        ));
        $item->setCurrent(true);
        
        $item = $menu->addChild('Mídias relacionadas', array(
            'uri' => '#tab2',
            'linkAttributes' => array(
                'id' => 'idT2',
                'data-toggle' => 'tab',
            )
        ));        

        return $menu;
    }

}
