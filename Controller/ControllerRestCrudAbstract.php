<?php
namespace SanSIS\Core\BaseBundle\Controller;

use SanSIS\Core\BaseBundle\Service\MessageService;
use SanSIS\Core\BaseBundle\Controller\ControllerAbstract;
use SanSIS\Core\BaseBundle\Entity\AbstractBase;
use Symfony\Component\HttpFoundation\Response;

class ControllerRestCrudAbstract extends ControllerCrudAbstract
{
    /**
     * Define o nome do método na service que será utilizado para 
     * realizar a pesquisa do CRUD
     * 
     * @var string
     */
    protected $searchQuery = 'searchQuery';

    /**
     * Define a view padrão para o create
     * 
     * @var string
     */
    protected $createView;
    
    /**
     * Define o nome da rota para o create
     * 
     * @var string
     */
    protected $createRoute;

    /**
     * Define a view padrão para o edit
     * 
     * @var string
     */
    protected $editView;

    /**
     * Define o nome da rota para o edit
     *
     * @var string
     */
    protected $editRoute;

    /**
     * Define a view padrão para o delete
     *
     * @var string
     */
    protected $deleteView;

    /**
     * Define o nome da rota para o delete
     *
     * @var string
     */
    protected $deleteRoute;

    /**
     * Define a rota padrão para o save em caso de sucesso
     * 
     * @var string
     */
    protected $saveSuccessRoute;

    public function searchAction()
    {
        $data = $this->getGridData();
        
        return $this->renderJson($data);
    }
    
    public function deleteAction(){
        
    	$data = $this->getService()->removeEntity($this->getRequest());
    	
    	return $this->renderJson($data);
    }
    
    public function getGridData()
    {
        $method = $this->searchQuery;
        $query = $this->getService()->$method($this->getRequest());
        
        $page = $this->getRequest()->query->get('page', 1);
        $rows = $this->getRequest()->query->get('rows');
         
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate( $query, $page , $rows );
        
        $data = new \StdClass();
        $data->page     =  $page;
        $data->total    =  ceil( $pagination->getTotalItemCount() / $rows);
        $data->records  =  $pagination->getTotalItemCount();
         
        $i = 0;
        $array = array();
        foreach ($pagination->getItems() as $item ){
            $j = 0;
            foreach($item as $key => $val){
                if ($j == 0) {
                    $array[$i]['id'] = $val;
                }
                else {
                    if (!isset($array[$i]['cell']))
                        $array[$i]['cell'] = array();
                    
                    $array[$i]['cell'][$key] = $val;
                }
                $j++;
            }
            
            $actions = '';
            
            if ($this->editRoute)
                $actions .= '<a title="Editar" href="'.$this->generateUrl($this->editRoute).'?id='.$array[$i]['id'].'" class="icon-edit"></a>';

            if ($this->viewRoute) {
                if ($this->editRoute) {
                    $actions .= '&nbsp;&nbsp;&nbsp;';
                }
                $actions .= '<a title="Visualizar" href="'.$this->generateUrl($this->viewRoute).'?id='.$array[$i]['id'].'" class="icon-eye-open"></a>';
            }
            
            if ($this->deleteRoute) {
                if ($this->editRoute || $this->viewRoute) {
                    $actions .= '&nbsp;&nbsp;&nbsp;';
                }
                $actions .= '<a title="Excluir" onclick="confirmarRemocao(\''.$array[$i]['id'].'\')" class="icon-trash"></a>';
            }
            if ($actions)
                $array[$i]['cell']['Acao'] = $actions;
        
            $i++;
        }
        
        $data->rows = $array;
        
        return $data;
    }

    public function createAction()
    {
        if ($this->get('session')->has('SanSISSaveResult') && $this->get('session')->get('SanSISSaveResult') instanceof AbstractBase) {
            $entityData = $this->get('session')->get('SanSISSaveResult')->toArray();
        } else {
            $entityData = $this->getService()->getRootEntityData();
        }

        if ($this->get('session')->has('SanSISSaveResult')) {
            $this->get('session')->remove('SanSISSaveResult');
        }
        
        $params = array(
            'formData'      => $this->getService()->getFormData(),
            'entityData'    => $entityData
        );
        
        return $this->render($this->createView, $params);
    }

    public function editAction()
    {
        if ($this->get('session')->has('SanSISSaveResult') && $this->get('session')->get('SanSISSaveResult') instanceof AbstractBase) {
            $entityData = $this->get('session')->get('SanSISSaveResult')->toArray();
        } else {
            $entityData = $this->getService()->getRootEntityData($this->getRequest()->query->get('id'));
        }
        
        if ($this->get('session')->has('SanSISSaveResult')){
            $this->get('session')->remove('SanSISSaveResult');
        }
        
        $params = array(
            'formData' => $this->getService()->getFormData(),
            'entityData' => $entityData
        );
        
        return $this->render($this->editView, $params);
    }
    


    public function viewAction()
    {
        $entityData = $this->getService()->getRootEntityData($this->getRequest()->query->get('id'));
    
        $params = array(
            'formData' => $this->getService()->getFormData(),
            'entityData' => $entityData
        );
    
        return $this->render($this->viewView, $params);
    }

    public function saveAction()
    {
        try {
            $this->getService()->save($this->getRequest());
            //MessageService::addMessage('success', 'MSG_S001');
            $this->addMessage('Dados salvos com sucesso.', 'success');
            $this->get('session')->set('SanSISSaveResult', true);
            return $this->redirectByRouteName($this->saveSuccessRoute);
        } catch (\Exception $e) {
        	$this->get('session')->set('SanSISSaveResult', $this->getService()->getRootEntity());
            $this->addMessage('Ocorreram erros ao executar a ação.', 'error');
            if (in_array($this->get('kernel')->getEnvironment(), array('dev')))
                $this->addMessage($e->getMessage(), 'error');
            return $this->redirectByReferer(302);
        }
    }
}
