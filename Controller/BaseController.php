<?php
namespace SanSIS\Core\BaseBundle\Controller;

use SanSIS\Core\BaseBundle\Controller\ControllerCrudAbstract;
use Symfony\Component\Security\Core\SecurityContextInterface;

class BaseController extends ControllerCrudAbstract
{
    /**
     * 
     * @var unknown
     */
    protected $service = 'base.service';
    
    protected $loginView = 'SanSISCoreBaseBundle:Default:login.html.twig';
    
    /**
     * Action para login utilizando a estrutura da SanSIS
     */
    public function loginAction()
    {
        $request = $this->get('request');
        $session = $request->getSession();
    
        // get the login error if there is one
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContextInterface::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
            if ($error->getMessage() == 'Bad credentials')
                $error = new \Exception('Usuário e/ou senha inválido(s).');
        } else {
            $error = '';
        }
    
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);
    
        return $this->render($this->loginView,
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    /**
     * Sobrescrita do método para só apresentar para root
     *
     * @param integer $id            
     * @return string
     */
    public function getDeleteGridAction($id, $status_tuple = null)
    {
        $roles = $this->container->get('security.context')
            ->getToken()
            ->getUser()
            ->getRoles();
        
        if (in_array('ROLE_ROOT', $roles) && $this->deleteRoute && $status_tuple != 2)
            return '<a title="Excluir" href="#" onclick="confirmarRemocao(\'' . $id . '\')" class="icon-trash" style="margin-right:5px;margin-left:5px"></a>';
        else
            return '';
    }

    /**
     * Sobrescrita para só root poder remover
     */
    public function deleteAction()
    {
        $user = $this->container->get('security.context')
            ->getToken()
            ->getUser();
        
        // Se por algum motivo não está autenticado - sessão caiu? - esta condição
        // corrige e envia o usuário para a página de login
        if (! is_object($user)) {
            header('Location: ' . $this->container->get('request')->getBaseUrl());
            exit();
        }
        
        $roles = $this->container->get('security.context')
            ->getToken()
            ->getUser()
            ->getRoles();
        
        if (in_array('ROLE_ROOT', $roles)) {
            $data = $this->getService()->removeEntity($this->getRequest());
            
            return $this->renderJson($data);
        } else {
            return $this->renderJson('Você não tem permissão para esta action');
        }
    }

    public function getStatesProvincesByCountryAction()
    {
        return $this->renderJson($this->getService()
            ->getStatesProvincesByCountry($this->getRequest()->query->get('idCountry')));
    }

    public function getCitiesByStateProvinceAction()
    {
        return $this->renderJson($this->getService()
            ->getCitiesByStateProvince($this->getRequest()->query->get('idStateProvince')));
    }

    public function getMeasureUnitiesByTypeAction()
    {
        return $this->renderJson($this->getService()
            ->getMeasureUnitiesByType($this->getRequest()->query->get('id')));
    }

    public function getOrganizationByNameAction()
    {
        $except = $this->getRequest()->query->has('identifier') ? $this->getRequest()->query->get('identifier') : null;
        
        return $this->render('SanSISCoreBaseBundle:Default:list.html.twig', array(
            'formData' => $this->getService()
                ->getOrganizationByName($this->getRequest()->query->get('query'), $except)
        ));
    }

    public function getEntityByNameAction()
    {
        $except = $this->getRequest()->query->has('identifier') ? $this->getRequest()->query->get('identifier') : null;
        
        return $this->render('SanSISCoreBaseBundle:Default:list.html.twig', array(
            'formData' => $this->getService()
                ->getEntityByName($this->getRequest()->query->get('query'), $except)
        ));
    }

    public function checkUniqueCpfAction()
    {
        return $this->renderJson($this->getService()
            ->checkUniqueCpf($this->getRequest()->query->get('id'), $this->getRequest()->query->get('cpf')));
    }

    public function checkUniqueUsernameAction()
    {
        return $this->renderJson($this->getService()
            ->checkUniqueUsername($this->getRequest()->query->get('id'), $this->getRequest()->query->get('username')));
    }
}
