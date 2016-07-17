<<<<<<< HEAD
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
=======
<?php

namespace SanSIS\Core\BaseBundle\Controller;

class DefaultController extends BaseController
{

    protected $service = 'account.service';

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
            'formData' => $this->getService()->getAccountFormData(),
        ));
    }

    /**
     * Mantém a sessão viva e também registra o usuário como on-line em algum serviço externo
     *
     * @return [type] [description]
     */
    public function keepAliveAction()
    {
        // Sessão de 60 minutos
        if (!$this->get('session')->has("keepAlive") || $this->getRequest()->query->has('reset') || $this->getRequest()->request->has('reset')) {
            $this->get('session')->set("keepAlive", time() + (3600));
        }

        //Tic tac - o tempo passando
        $tempo = (int) $this->get('session')->get("keepAlive") - time();

        //Usuário definido como on-line a cada minuto.
        //Qualquer usuário com mais de dois minutos de lag será considerado offline
        //@TODO
        if (($tempo % 60) == 0) {

        }

        if ($tempo <= 0) {
            //Define usuário como offline
            //@TODO
            //
            //Destrói a sessão do usuário
            $this->get('session')->clear();
            return $this->renderJson(array("keepAlive" => 0, "time" => $tempo, 'hasSession' => false));
        }

        return $this->renderJson(array("keepAlive" => 1, "time" => $tempo));
    }
}
>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
