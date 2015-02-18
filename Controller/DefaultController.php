<?php

namespace SanSIS\Core\BaseBundle\Controller;

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

    public function accountAction()
    {
        $entityData = $this->getService()->getUserData();
        $formData   = $this->getService()->getAccountFormData();
        return $this->render('SanSISCoreBaseBundle:Default:account.html.twig');
    }

    public function accountSaveAction()
    {
        return $this->render('SanSISCoreBaseBundle:Default:account.html.twig');
    }
}
