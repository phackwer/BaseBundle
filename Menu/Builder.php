<?php

namespace SanSIS\Core\BaseBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Extendendo ContainerAware
 * https://github.com/KnpLabs/KnpMenuBundle/blob/master/Resources/doc/index.md#create-your-first-menu
<<<<<<< HEAD
 * 
=======
 *
>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
 * @author phackwer
 */

class Builder extends ContainerAware
{

    public function menu(FactoryInterface $factory, array $options)
    {
        $user = $this->container->get('security.context')
        ->getToken()
        ->getUser();
<<<<<<< HEAD
        
=======

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
        //Se por algum motivo não está autenticado - sessão caiu? - esta condição
        //corrige e envia o usuário para a página de login
        if (!is_object($user)){
            header('Location: '.$this->container->get('request')->getBaseUrl());
            exit();
        }
<<<<<<< HEAD
        
=======

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
        $roles = $this->container->get('security.context')
        ->getToken()
        ->getUser()
        ->getRoles();
<<<<<<< HEAD
        
        $menu = $factory->createItem('root');
        
=======

        $menu = $factory->createItem('root');

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
        $menu->addChild('Início', array(
            'route' => 'overview',
            'routeAbsolute' => true
        ));
<<<<<<< HEAD
        
        $entities = $menu->addChild('Entidades', array(
            'uri' => '#'
        ));
    
=======

        $entities = $menu->addChild('Entidades', array(
            'uri' => '#'
        ));

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
        $entities->addChild('Listar / pesquisar', array(
            'route' => 'entity_index',
            'routeAbsolute' => true
        ));
<<<<<<< HEAD
    
=======

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
        $entities->addChild('Criar novo registro', array(
            'route' => 'entity_create',
            'routeAbsolute' => true
        ));
<<<<<<< HEAD
        
        $sistema = $menu->addChild('Sistema', array(
            'uri' => '#'
        ));
        
=======

        $sistema = $menu->addChild('Sistema', array(
            'uri' => '#'
        ));

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
            $about = $sistema->addChild('Sobre o sistema', array(
                'uri' => '#aboutDialog',
                'data-toggle' => 'modal'
            ));
            $about->setLinkAttribute('data-toggle', 'modal');
<<<<<<< HEAD
        
            $sistema->addChild('Minha conta', array(
                'route' => 'overview',
//                 'route' => 'account',
                'routeAbsolute' => true
            ));
            
=======

            $sistema->addChild('Minha conta', array(
                'route' => 'account',
                'routeAbsolute' => true
            ));

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
            if (
                in_array('ROLE_ADMIN', $roles) ||
                in_array('ROLE_ROOT', $roles)
                ) {
<<<<<<< HEAD
        
                $admin = $sistema->addChild('Administração', array(
                    'uri' => '#'
                ));
    
                    $users = $admin->addChild('Usuários', array(
                        'uri' => '#'
                    ));
                    
=======

                $admin = $sistema->addChild('Administração', array(
                    'uri' => '#'
                ));

                    $users = $admin->addChild('Usuários', array(
                        'uri' => '#'
                    ));

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
                        $users->addChild('Listar / pesquisar', array(
                            'route' => 'user_index',
                            'routeAbsolute' => true
                        ));
<<<<<<< HEAD
                        
=======

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
                        $users->addChild('Criar novo registro', array(
                            'route' => 'user_create',
                            'routeAbsolute' => true
                        ));
<<<<<<< HEAD
                        
                    $profiles = $admin->addChild('Perfis de acesso', array(
                            'uri' => '#'
                        ));
                        
=======

                    $profiles = $admin->addChild('Perfis de acesso', array(
                            'uri' => '#'
                        ));

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
                        $profiles->addChild('Listar / pesquisar', array(
                            'route' => 'profile_index',
                            'routeAbsolute' => true
                        ));
<<<<<<< HEAD
                        
=======

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
                        $profiles->addChild('Criar novo registro', array(
                            'route' => 'profile_create',
                            'routeAbsolute' => true
                        ));
                }
<<<<<<< HEAD
                
=======

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
            $sistema->addChild('Sair', array(
                'route' => 'logout',
                'routeAbsolute' => true
            ));
<<<<<<< HEAD
            
=======

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
        return $menu;
    }

}
