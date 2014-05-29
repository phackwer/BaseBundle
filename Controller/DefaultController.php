<?php

namespace Ibram\Core\BaseBundle\Controller;

use Ibram\Core\BaseBundle\Controller\ControllerAbstract;

use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends ControllerAbstract
{
    public function indexAction()
    {
        return $this->render('IbramCoreBaseBundle:Default:index.html.twig');
    }
    
    public function logoutAction()
    {
        $this->getRequest()->getSession()->clear();
        return $this->redirectByRouteName('Ibram_core_base');
    }

    public function sessionInfoAction() {
        return $this->render(
            "IbramCoreBaseBundle:Default:session.html.twig",
            array(
                'nome'      => @$_SESSION['NO_PESSOA'],
                'cpf'       => @$_SESSION['NU_CPF_CNPJ_PESSOA'],
                'perfil'    => @$_SESSION['DS_PERFIL'],
                'versao'    => @$_SESSION['sysVersion'],
            )
        );
    }
}
