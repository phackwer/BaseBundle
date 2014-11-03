<?php
namespace SanSIS\Core\BaseBundle\Controller;

use SanSIS\Core\BaseBundle\Service\MessageService;
use SanSIS\Core\BaseBundle\Controller\ControllerAbstract;
use SanSIS\Core\BaseBundle\Entity\AbstractBase;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use \Doctrine\ORM\Query;

/**
 * Classe que implementa um controller padrão para CRUDs
 * @tutorial - Estenda e roteie as actions no routing do seu bundle.
 *           - Roteie apenas o que for utilizar
 *           - Evite rotear o delete nos casos onde não pode haver
 *             exclusão para evitar que a funcionalidade seja disparada
 *           - Nem sempre o CRUD atenderá de forma completa, sobrescreva
 *             qualquer método que julgar necessário
 *           - Caso precise de mais ações na coluna de ações, sobrescreva
 *             o método getGridActions.
 *
 * @author pablo.sanchez
 *
 */
abstract class ControllerCrudAbstract extends ControllerAbstract
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
     * Permite a criação de títulos padronizados para as ações de CRUD
     * 
     * @var unknown
     */
    protected $createFormAction = 'Criar novo';

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
     * Permite a criação de títulos padronizados para as ações de CRUD
     * 
     * @var unknown
     */
    protected $editFormAction = 'Editar';

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
     * Permite a criação de títulos padronizados para as ações de CRUD
     * 
     * @var unknown
     */
    protected $deleteFormAction = 'Excluir';

    /**
     * Define o nome da rota para o delete
     *
     * @var string
     */
    protected $deleteRoute;

    /**
     * Define a view padrão para o view
     *
     * @var string
     */
    protected $viewView;
    
    /**
     * Permite a criação de títulos padronizados para as ações de CRUD
     * 
     * @var unknown
     */
    protected $viewFormAction = 'Visualizar';

    /**
     * Define o nome da rota para o view
     *
     * @var string
     */
    protected $viewRoute;

    /**
     * Define a rota padrão para o save em caso de sucesso
     *
     * @var string
     */
    protected $saveSuccessRoute;
    
    /****************************************************************************************
     * DOCTRINE
     */

    /**
     * Realiza a pesquisa paginada
     * @return \StdClass
     */
    public function getGridData()
    {
        //Busca a query que será utilizada na pesquisa para a grid
        $method = $this->searchQuery;
        $query = $this->getService()->$method($this->getRequest());

        //pagina a ser retornada
        $page = $this->getRequest()->query->get('page', 1);
        //quantidade de linhas a serem retornadas por página
        $rows = $this->getRequest()->query->get('rows');
        
        $query->setFirstResult($page * $rows - $rows)
              ->setMaxResults($rows);
        
        $pagination = new Paginator($query, true);
        
        //Objeto de resposta
        $data = new \StdClass();
        $data->page     =  $page;
        $data->total    =  ceil( $pagination->count() / $rows);
        $data->records  =  $pagination->count();
        //linhas da resposta - o método abaixo pode (e provavelmente deve)
        //ser implantado de acordo com as necessidades da aplicação
        $data->rows     = $this->prepareGridRows($pagination);
        
        return $data;
    }
    
    /**
     *  Prepara a resposta para o Grid processando cada uma das linhas retornadas
     *  e acrescentando automaticamente uma coluna de Ação
     *
     * @param Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination  $pagination
     */
    public function prepareGridRows(\Doctrine\ORM\Tools\Pagination\Paginator  $pagination)
    {
        $i = 0;
        $array = array();
        $id = null;
        
        foreach ($pagination as $item) {
            $j = 0;
            
            foreach($item as $key => $val){
                if ($j == 0) {
                    $id = $array[$i]['id'] = $val;
                    $subcells = $this->getService()->getSearchSubCells($id);
                    
                    if ($subcells){
                        foreach ($subcells as $skey => $sval) {
                            if (!isset($array[$i]['cell'])) {
                                $array[$i]['cell'] = array();
                            }
                            
                            if ($val instanceof \DateTime) {
                                $sval = $sval->format('d/m/Y');
                            }
                            
                            $array[$i]['cell'][$skey] = '<div class="jqGridOverflowColumn">'.$sval.'</div>';
                        }
                    }
                    
                }
                else {
                    if (!isset($array[$i]['cell'])) {
                            $array[$i]['cell'] = array();
                    }

                    if ($val instanceof \DateTime) {
                            $val = $val->format('d/m/Y');
                    }

                    $array[$i]['cell'][$key] = '<div class="jqGridOverflowColumn">'.$val.'</div>';;
                }
                $j++;
                    
                //Aqui é adicionada a coluna de ações
                $actions = $this->getGridActions($array[$i]['id'], $item);
                
                if ($actions) {
                    $array[$i]['cell']['Acao'] = $actions;
                    $array[$i]['cell']['acao'] = $actions;
                }
            }
            
            $i++;
        }
        
        return $array;
    }

    /**
     * Retorna o link para a ação de Edição no Grid
     *
     * @param integer $id
     * @return string
     */
    public function getEditGridAction($id, $status_tuple = null)
    {
        if ($this->editRoute && $status_tuple != 2)
    	   return '<a title="Editar" href="'.$this->generateUrl($this->editRoute).'?id='.$id.'" class="icon-edit" style="margin-right:5px;margin-left:5px"></a>';
        else
           return '';
    }

    /**
     * Retorna o link para a ação de Visualização no Grid
     *
     * @param integer $id
     * @param string $viewRoute - rota forçada - para relatórios (?!)
     * @return string
     */
    public function getViewGridAction($id, $viewRoute = null)
    {
        try {
            if ($this->viewRoute || $viewRoute)
                return '<a title="Visualizar" href="' . $this->generateUrl($viewRoute ? $viewRoute : $this->viewRoute) . '?id=' . $id . '" class="icon-eye-open" style="margin-right:5px;margin-left:5px"></a>';
            else
                return '';
        } catch (RouteNotFoundException $e) {
            if (!strstr($viewRoute, 'http://')) {
                $niveis = count(explode('/',\AppKernel::getInstance()->getAppDir()));
                $path = '';
                for ($i=0; $i < $niveis; $i++){
                    $path .= '../';
                }
                $viewRoute = $path.$viewRoute;
            }
            return '<a title="Visualizar" href="' . $viewRoute . $id . '" class="icon-eye-open" style="margin-right:5px;margin-left:5px"></a>';
        }
    }

    /**
     * Retorna o link para a ação de Remoção no Grid
     *
     * @param integer $id
     * @return string
     */
    public function getDeleteGridAction($id, $status_tuple = null)
    {
        if ($this->deleteRoute && $status_tuple != 2)
    	   return '<a title="Excluir" href="#" onclick="confirmarRemocao(\''.$id.'\')" class="icon-trash" style="margin-right:5px;margin-left:5px"></a>';
        else
           return '';
    }

    /**
     * Retorna os links que populam a coluna de ação no grid
     * - Pode ser sobrescrito para a inclusão de novas ações!!!
     *
     * @param integer $id
     * @return string
     */
    public function getGridActions($id, $item = null)
    {
        $statusTuple = null;
        if (isset($item['statusTuple'])){
            $statusTuple = $item['statusTuple'];
        }
        $actions = '';
        $actions .= $this->getViewGridAction($id);
        $actions .= $this->getEditGridAction($id, $statusTuple);
        $actions .= $this->getDeleteGridAction($id, $statusTuple);

        return $actions;
    }

    /**
     * Action que deve ser mapeada para realizar a pesquisa e popular uma grid
     */
    public function searchAction()
    {
        $data = $this->getGridData();
        return $this->renderJson($data);
    }

    /**
     * Action que deve ser mapeada para criação de registros
     */
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
            'formTitleAction'   => $this->createFormAction,
            'formData'          => $this->getService()->getFormData($entityData),
            'entityData'        => $entityData
        );

        return $this->render($this->createView, $params);
    }

    /**
     * Action que deve ser mapeada para edição de registros
     */
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
            'formTitleAction'   => $this->editFormAction,
            'formData'          => $this->getService()->getFormData($entityData),
            'entityData'        => $entityData
        );

        return $this->render($this->editView, $params);
    }

    /**
     * Action que deve ser mapeada para visualização de registros
     */
    public function viewAction()
    {
        $entityData = $this->getService()->getRootEntityData($this->getRequest()->query->get('id'));

        $params = array(
            'formTitleAction'   => $this->viewFormAction,
            'formData' => $this->getService()->getFormData($entityData),
            'entityData' => $entityData
        );

        return $this->render($this->viewView, $params);
    }

    /**
     * Action que deve ser mapeada para salvar os registros no banco de dados
     */
    public function saveAction()
    {
    	
        try {
            $this->getService()->save($this->getRequest());
            MessageService::addMessage('success', 'MSG_S001');
            $this->get('session')->set('SanSISSaveResult', true);
            if ($this->saveSuccessRoute == $this->editRoute){
                $id = $this->getService()->getRootEntity()->getId();
                return $this->redirectByRouteName($this->saveSuccessRoute, 302, array('id' => $id));
            }
            else {
                return $this->redirectByRouteName($this->saveSuccessRoute);
            }
        } catch (\Exception $e) {
        	$this->get('session')->set('SanSISSaveResult', $this->getService()->getRootEntity());
            MessageService::addMessage('error', 'MSG_E000');
            if (in_array($this->get('kernel')->getEnvironment(), array('dev')))
                $this->addMessage(
                    $e->getMessage()."\n".
                    $e->getLine()."\n".
                    $e->getCode()."\n".
                    $e->getTraceAsString()."\n"
                    , 'error');
            return $this->redirectByReferer(302);
        }
    }

    /**
     * Action que deve ser mapeada para excluir os registros do banco de dados
     */
    public function deleteAction(){

        $data = $this->getService()->removeEntity($this->getRequest());

        return $this->renderJson($data);
    }
}
