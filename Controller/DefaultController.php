<?php

namespace SanSIS\Core\BaseBundle\Controller;

use SanSIS\Core\BaseBundle\Controller\ControllerAbstract;

use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends ControllerAbstract
{
    public function indexAction()
    {
        return $this->render('SanSISCoreBaseBundle:Default:index.html.twig');
    }
    
    public function logoutAction()
    {
        $this->getRequest()->getSession()->clear();
        return $this->redirectByRouteName('sansis_core_base_homepage');
    }
}
