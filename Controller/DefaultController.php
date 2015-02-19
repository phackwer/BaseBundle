<?php

namespace SanSIS\Core\BaseBundle\Controller;

class DefaultController extends BaseController
{

    protected $service = 'memora_core.account.service';

    protected $saveSuccessRoute = 'overview';

    protected $accountView = 'SanSISCoreBaseBundle:Default:account.html.twig';

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
        return $this->render($this->accountView, array(
            'entityData' => $this->getService()->getUserData(),
            'formData'   => $this->getService()->getAccountFormData()
        ));
    }
}
