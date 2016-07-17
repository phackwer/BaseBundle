<?php

namespace SanSIS\Core\BaseBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Extendendo ContainerAware
 * https://github.com/KnpLabs/KnpMenuBundle/blob/master/Resources/doc/index.md#create-your-first-menu
 * 
 * @author phackwer
 */

class Builder extends ContainerAware
{

    public function menu(FactoryInterface $factory, array $options)
    {
        $user = $this->container->get('security.context')
        ->getToken()
        ->getUser();
        
        //Se por algum motivo não está autenticado - sessão caiu? - esta condição
        //corrige e envia o usuário para a página de login
        if (!is_object($user)){
            header('Location: '.$this->container->get('request')->getBaseUrl());
            exit();
        }
        
        $roles = $this->container->get('security.context')
        ->getToken()
        ->getUser()
        ->getRoles();
        
        $menu = $factory->createItem('root');
        
        $menu->addChild('Início', array(
            'route' => 'overview',
            'routeAbsolute' => true
        ));
        
        $entities = $menu->addChild('Entidades', array(
            'uri' => '#'
        ));
    
        $entities->addChild('Listar / pesquisar', array(
            'route' => 'entity_index',
            'routeAbsolute' => true
        ));
    
        $entities->addChild('Criar novo registro', array(
            'route' => 'entity_create',
            'routeAbsolute' => true
        ));
        
        $sistema = $menu->addChild('Sistema', array(
            'uri' => '#'
        ));
        
            $about = $sistema->addChild('Sobre o sistema', array(
                'uri' => '#aboutDialog',
                'data-toggle' => 'modal'
            ));
            $about->setLinkAttribute('data-toggle', 'modal');
        
            $sistema->addChild('Minha conta', array(
                'route' => 'overview',
//                 'route' => 'account',
                'routeAbsolute' => true
            ));
            
            if (
                in_array('ROLE_ADMIN', $roles) ||
                in_array('ROLE_ROOT', $roles)
                ) {
        
                $admin = $sistema->addChild('Administração', array(
                    'uri' => '#'
                ));
    
                    $users = $admin->addChild('Usuários', array(
                        'uri' => '#'
                    ));
                    
                        $users->addChild('Listar / pesquisar', array(
                            'route' => 'user_index',
                            'routeAbsolute' => true
                        ));
                        
                        $users->addChild('Criar novo registro', array(
                            'route' => 'user_create',
                            'routeAbsolute' => true
                        ));
                        
                    $profiles = $admin->addChild('Perfis de acesso', array(
                            'uri' => '#'
                        ));
                        
                        $profiles->addChild('Listar / pesquisar', array(
                            'route' => 'profile_index',
                            'routeAbsolute' => true
                        ));
                        
                        $profiles->addChild('Criar novo registro', array(
                            'route' => 'profile_create',
                            'routeAbsolute' => true
                        ));
                }
                
            $sistema->addChild('Sair', array(
                'route' => 'logout',
                'routeAbsolute' => true
            ));
            
        return $menu;
    }

}
