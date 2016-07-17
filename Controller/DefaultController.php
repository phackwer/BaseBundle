<?php

namespace SanSIS\Core\BaseBundle\Controller;

use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends BaseController
{
    public function indexAction()
    {
        return $this->render('SanSISCoreBaseBundle:Default:index.html.twig');
    }
    
    public function overviewAction()
    {
        return $this->render('SanSISCoreBaseBundle:Default:index.html.twig');
    }
    
    public function logoutAction()
    {
        $this->getRequest()->getSession()->clear();
        return $this->redirectByRouteName('sansis_core_base_homepage');
    }
}
