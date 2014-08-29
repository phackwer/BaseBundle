<?php
namespace SanSIS\Core\BaseBundle\ServiceLayer;

use \Symfony\Component\HttpFoundation\Request;
use \Doctrine\ORM\Query;
use \SanSIS\Core\BaseBundle\ServiceLayer\ServiceAbstract;
use \SanSIS\Core\BaseBundle\ServiceLayer\Exception\NoRootEntityException;
use \SanSIS\Core\BaseBundle\ServiceLayer\Exception\NoImplementationException;
use \SanSIS\Core\BaseBundle\ServiceLayer\Exception\WrongTypeRootEntityException;
use \SanSIS\Core\BaseBundle\ServiceLayer\Exception\ValidationException;
use \SanSIS\Core\BaseBundle\EntityRepository\AbstractBase as EntityRepository;
use \SanSIS\Core\BaseBundle\Entity\AbstractBase as Entity;

abstract class EntityServiceAbstract extends ServiceAbstract
{

    /**
     * @var string - nome da classe da entidade raiz da service
     */
    protected $rootEntityName = null;

    /**
     * @var string - nome da PK da entidade raiz da service
     */
    protected $rootEntityIdName = 'id';

    /**
     * @var \SanSIS\Core\BaseBundle\Entity\AbstractBase
     */
    protected $rootEntity = null;

    /**
     * @var \SanSIS\Core\BaseBundle\EntityRepository\AbstractBase
     */
    protected $rootRepository = null;

    /**
     * @var \Knp\Component\Pager\Paginator
     */
    protected $paginator = null;

    /**
     * @var \Symfony\Component\Validator\Validator
     */
    protected $validator = null;
    
    /**
     * @var array - entidades dentro da entidade root para processamento de persistência
     */
    protected $innerEntities = array();

    /**
     *
     * @throws NoRootEntityException
     * @return string
     */
    protected function getRootEntityName()
    {
        if (is_null($this->rootEntityName)) {
            throw new NoRootEntityException();
        }
        
        return $this->rootEntityName;
    }

    /**
     * Obtém o repositório da entidade raiz mapeada para persistência pela
     * 
     * @throws NoRootEntityException
     * @return \SanSIS\Core\BaseBundle\EntityRepository\AbstractBase
     */
    protected function getRootRepository()
    {
        if (is_null($this->rootEntityName)) {
            throw new NoRootEntityException();
        }
        
        if (is_null($this->rootRepository)) {
            $this->rootRepository = $this->getEntityManager()->getRepository($this->getRootEntityName());
        }
        
        return $this->rootRepository;
    }

    /**
     * Retorna um array com os dados de apoio do formulário
     *
     * @return array
     */
    public function getFormData()
    {
        return array();
    }

    /**
     * Retorna um array com os dados da entidade raiz
     *
     * @return array
     */
    public function getRootEntityData($id = null)
    {
        return $this->getRootEntity($id)->toArray();
    }

    /**
     * Obtém a entidade raiz da service já mapeada para persistência pelo EntityManager
     *
     * @param string $id            
     * @throws NoRootEntityException
     * @return \SanSIS\Core\BaseBundle\Entity\AbstractBase
     */
    public function getRootEntity($id = null)
    {
        if (is_null($this->rootEntityName)) {
            throw new NoRootEntityException();
        }
        
        if ($id) {
            $this->rootEntity = $this->getRootRepository()->find($id);
        }
        
        if (! $this->rootEntity) {
            $class = $this->rootEntityName;
            $this->rootEntity = new $class();
            $this->getEntityManager()->persist($this->rootEntity);
        }
        
        return $this->rootEntity;
    }

    /**
     *
     * @param \SanSIS\Core\BaseBundle\Entity\AbstractBase $entity            
     * @throws WrongTypeRootEntityException
     * @return \SanSIS\Core\BaseBundle\Entity\AbstractBase
     */
    public function setRootEntity(Entity $entity)
    {
        if (get_class != $this->rootEntityName()) {
            throw new WrongTypeRootEntityException();
        }
        
        return $this->rootEntity = $entity;
    }

    /**
     * Método para automatizar o mapeamento de uma entidade para o flush
     *
     * @param Request $req
     */
    public function setRootEntityForFlush(Request $req)
    {
        $id = null;
        if ($this->rootEntityIdName) {
            if ($req->query->has($this->rootEntityIdName)) {
                $id = $req->query->get($this->rootEntityIdName);
            } else {
                $id = $req->request->get($this->rootEntityIdName);
            }
        }
        $this->getRootEntity($id);
    }

    /**
     * Preenche os dados da entidade com os recebidos pela Service de
     * forma genérica.
     * Deve ser reimplementado nos casos em que o
     * objeto for complexo
     *
     * @param \Symfony\Component\HttpFoundation\Request $req            
     * @throws NoImplementationException
     */
    public function populateRootEntity(Request $req)
    {
        $ref = new \ReflectionClass($this->rootEntity);
        
        $methods = get_class_methods($this->rootEntity);
         
        foreach ($methods as $method) {
            if ('set' === substr($method, 0, 3) && $method != 'setStatusTuple') {
        
                $attr = lcfirst(substr($method, 3));
                if ($req->query->has($attr)) {
                    $value = $req->query->get($attr);
                } else {
                    $value = $req->request->get($attr);
                }
        
                $params = $ref->getMethod($method)->getParameters();
                $strDoc = $ref->getMethod($method)->getDocComment();
                $class = '';
        
                if ($params[0]->getClass()) $class = $params[0]->getClass()->name;
        
                if (strstr($strDoc,'\DateTime') || $class == 'DateTime') {
                    $class = '\DateTime';
        
                    if ($value) {
                        if (strstr($value, ':'))
                            $value = $class::createFromFormat('d/m/Y h:m:i', $value);
                        else
                            $value = $class::createFromFormat('d/m/Y', $value);
                    }
                    else {
                        //corrige casos de strings vazias para datas
                        $value = null;
                    }
                }
                else if ($class && strstr($method, 'set') && is_array($value)) {
                    if (!count($this->innerEntities)){
                        $this->innerEntities = array('root'=>$this->rootEntity,'innerEntities' => array());
                    }                    
                    $allInt = true;
                    foreach($value as $key=>$val) {
                        if (!is_int($key)) {
                            $allInt = false;
                        }
                    }
                    if ($allInt){
                        foreach($value as $key=>$val){
                            $this->rootEntity->$method($this->populateInnerEntity($class, $val, $this->rootEntity, $this->innerEntities));
                        }
                        continue;
                    }
                    else {
                        $value = $this->populateInnerEntity($class, $value, $this->rootEntity, $this->innerEntities);
                    }
                }
                else if ($class && strstr($method, 'setId')) {
                    $value = $this->getEntityManager()->getRepository($class)->findOneBy(array('id' => $value));
                }
                 
                $this->rootEntity->$method($value);
            }
        }
    }
    
    /**
     * Preenche os dados de entidades mapeadas abaixo da atual
     * @param string $class - nome da classe
     * @param array $values - valores para popular o objeto
     * @return SanSIS\Core\BaseBundle\Entity\AbstractBase
     */
    public function populateInnerEntity($newClass, $values, $parent, &$innerEntities = null)
    {
        $newEntity = new $newClass();
        $this->registerInnerEntityHelper($newEntity, $innerEntities);
        
        $class = explode('\\', get_class($parent));
        
        $class = $class[count($class) - 1];
        
        $method = 'setId'.$class;
        $newEntity->$method($parent);
        
        $ref = new \ReflectionClass($newEntity);
        
        $methods = get_class_methods($newEntity);
         
        foreach ($methods as $method) {
            if ('set' === substr($method, 0, 3) && $method != 'setStatusTuple') {
                $attr = lcfirst(substr($method, 3));
                if (isset($values[$attr])) {
                    $value = $values[$attr];
                }
                else 
                    $value=null;
            
                $params = $ref->getMethod($method)->getParameters();
                $strDoc = $ref->getMethod($method)->getDocComment();
                $class = '';
                
                if ($params[0]->getClass()) $class = $params[0]->getClass()->name;
                
                if (strstr($strDoc,'\DateTime') || $class == 'DateTime') {
                    $class = '\DateTime';
                
                    if ($value) {
                        if (strstr($value, ':'))
                            $value = $class::createFromFormat('d/m/Y h:m:i', $value);
                        else
                            $value = $class::createFromFormat('d/m/Y', $value);
                    }
                    else {
                        //corrige casos de strings vazias para datas
                        $value = null;
                    }
                }
                else if ($class && strstr($method, 'set') && is_array($value)) {
                    $value = $this->populateEntity($class, $value, $newEntity, $innerEntities['innerEntities']);
                }
                else if ($class && strstr($method, 'setId')) {
                    $value = $this->getEntityManager()->getRepository($class)->findOneBy(array('id' => $value));
                }
                 
                $newEntity->$method($value);
            }
        }
        
        return $newEntity;
    }
    
    protected function registerInnerEntityHelper($entity, &$array = null)
    {
        $array['innerEntities'][] = array('root'=>$entity,'innerEntities' => array());
    }
    
    protected function flushInnerEntities()
    {
        $this->proccessInnerEntities($this->innerEntities['root'], $this->innerEntities['innerEntities']);
    }
    
    protected function proccessInnerEntities($parent, &$entities)
    {
        foreach ($entities as $key=>$entity){
            $class = explode('\\', get_class($parent));
            $class = $class[count($class) - 1];
            $method = 'setId'.$class;
            $entity['root']->$method($parent);

            $this->getEntityManager()->persist($entity['root']);
            $this->getEntityManager()->flush();
            
            if (count($entity['innerEntities'])) {
                $this->proccessInnerEntities($entity['root'], $entity['innerEntities']);
            }
        }
    }

    /**
     * Regras de validação de dados da entidade
     *
     * @example Tipos de dados incompatíveis
     *         
     * @throws NoImplementationException
     */
    public function validateRootEntity()
    {
        $this->validate($this->getRootEntity());
    }

    /**
     * Regras de verificação de regras de negócio sobre a entidade
     *
     * @example Soma de recursos financeiros já persistidos no banco extrapolam o
     *          total permitido quando somados à nova entidade
     *         
     * @throws NoImplementationException
     */
    public function verifyRootEntity()
    {}

    public function flushRootEntity(Request $req)
    {
    	
        try {
            $this->setRootEntityForFlush($req);
            $this->populateRootEntity($req);
            $this->validateRootEntity();
            $this->verifyRootEntity();
            $this->getEntityManager()->flush();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     */
    public function preSave(Request $req)
    {
        return $req;
    }

    /**
     */
    public function save(Request $req)
    {
//         try{
            if ($this->preSave($req)) {
//                 $this->getEntityManager()
//                      ->getConnection()
//                      ->beginTransaction();
                $this->flushRootEntity($req);
                $this->flushInnerEntities($this->innerEntities['innerEntities']);
//                 $this->getEntityManager()
//                      ->getConnection()
//                      ->commit();
                $this->postSave($req);
            }
//         }
//         catch(\Exception $e){
//             $this->getEntityManager()
//                  ->getConnection()
//                  ->rollback();
//         }
    }

    /**
     */
    public function postSave(Request $req)
    {
        return $req;
    }
    
    public function checkStatusTuple() {
        $this->getRootEntity();
        $logic = false;
        $methods = get_class_methods($this->rootEntity);
        foreach ($methods as $method) {
            if ($method == 'setStatusTuple'){
                $logic = true;
                break;
            }
        }
    }
    
    public function removeEntity(Request $req)
    {
        $id = null;
        if ($this->rootEntityIdName) {
            if ($req->query->has($this->rootEntityIdName)) {
                $id = $req->query->get('id');
            } else {
                $id = $req->request->get('id');
            }
        }
        $this->getRootEntity($id);
        
        if (!$this->checkStatusTuple()) {
            $this->getEntityManager()->remove($this->rootEntity);
        }
        else {
            $this->rootEntity->setStRegistroAtivo('S');
        }

        $this->getEntityManager()->flush();
        
        return true;
    }

    /**
     *
     * @param
     *            \Knp\Component\Pager\Paginator
     */
    public function setPaginator($paginator)
    {
        $this->paginator = $paginator;
        
        return $this;
    }

    /**
     *
     * @return \Knp\Component\Pager\Paginator
     */
    public function getPaginator()
    {
        if (is_string($this->paginator)) {
            $class = $this->paginator;
            $this->paginator = new $class();
        }
        
        return $this->paginator;
    }

    /**
     *
     * @param
     *            \Symfony\Component\Validator\Validator
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;
        
        return $this;
    }

    /**
     *
     * @return \Symfony\Component\Validator\Validator
     */
    public function getValidator()
    {
        if (is_string($this->validator)) {
            $class = $this->validator;
            $this->validator = new $class();
        }
        
        return $this->validator;
    }

    /**
     *
     * @param mixed $entity            
     * @param string[] $groups            
     * @throws ServiceValidationException
     */
    protected function validate($entity, $groups = array())
    {
        if ($this->getValidator()) {
            $validation = ($this->getValidator()->validate($entity, $groups));
            if (count($validation)) {
                throw new ValidationException($validation);
            }
        }
    }
    
    function getSearchQuery($searchData)
    {
        return $this->getRootRepository()->getSimpleGridSearchQuery($searchData);
    }

    function searchQuery(Request $req)
    {
        $keys = $req->query->keys();
        $searchData = array();
        foreach ($keys as $key) {
            $searchData[$key] = $req->query->get($key);
        }
        
        if (isset($searchData['sidx'])) {
            $orderby = $searchData['sidx'];
            $order = $searchData['sord'];
        }
        
        unset($searchData['rows']);
        unset($searchData['page']);
        unset($searchData['sidx']);
        unset($searchData['sord']);
        unset($searchData['nd']);
        unset($searchData['_search']);
        
        $query = $this->getSearchQuery($searchData);
        
        if ($this->checkStatusTuple()){
            $query->setDQL($query->getDQL() . $and . 'g.statusTuple <> \'N\'');
        }
        
        if (isset($orderby)) {
            $query->setDQL($query->getDQL() . ' order by ' . $orderby . ' ' . $order);
        }
        
        return $query->setHydrationMode(Query::HYDRATE_ARRAY);
    }
}