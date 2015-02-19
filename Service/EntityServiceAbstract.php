<?php
namespace SanSIS\Core\BaseBundle\Service;

use \Doctrine\ORM\Query;
use \SanSIS\Core\BaseBundle\Entity\AbstractBase as Entity;
use \SanSIS\Core\BaseBundle\Service\Exception\NoRootEntityException;
use \SanSIS\Core\BaseBundle\Service\Exception\ValidationException;
use \SanSIS\Core\BaseBundle\Service\Exception\WrongTypeRootEntityException;
use \SanSIS\Core\BaseBundle\Service\ServiceAbstract;
use \Symfony\Component\HttpFoundation\Request;

/**
 * @TODO Tratar Files no upload - deverá ser antes do flush para utilizar a
 * mesma transaction e ter, de alguma forma, a remoção dos arquivos subidos
 * em caso de exceção
 *
 * @author phackwer
 */
abstract

class EntityServiceAbstract extends ServiceAbstract
{
    /**
     * Suspende a persistência e printa a entidade populada na tela.
     * @var bool
     */
    public $debug = false;

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
     * @param array $entityData - dados para auxiliar na busca de informações para o formulário (ex: lista de UFs e Cidades)
     * @return array
     */
    public function getFormData($entityData = null)
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
     * Retorna um array com os dados da entidade raiz vazia para a criação
     * Sobrescreva caso precise da entidade pré-populada
     *
     * @return array
     */
    public function getNewRootEntityData()
    {
        return $this->getRootEntity()->toArray();
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

        if (!$this->rootEntity) {
            $class            = $this->rootEntityName;
            $this->rootEntity = new $class();
            if (!$this->debug) {
                $this->getEntityManager()->persist($this->rootEntity);
            }
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
        $entType = $this->getRootEntityName();
        if (strpos($entType, '\\') == 0) {
            $entType = substr($entType, 1);
        }

        if (get_class($entity) != $entType) {
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
        $this->populateEntities($req, $this->rootEntity, null);
        if ($this->debug) {
            echo '<pre>';
            print_r($_POST);
            die;
            //só descomente em casos extremos
            //print_r($this->rootEntity);
            die;
        }
    }

    /**
     * Infected - Bad Religion
     *
     * Now here I go
     * hope I don't break down
     * I won't take anything
     * I don't need anything
     * Don't wanna exist
     * I can't persist
     * Please stop before I do it again
     *
     * @param mixed $values Valores a serem salvos
     * @param mixed $newClass objeto ou nome da classe
     * @param SanSIS\Core\BaseBundle\Entity\AbstractBase $parent Entidade pai da classe criada
     * @param string $arrayClass - innerEntity de uma Collection
     * @return SanSIS\Core\BaseBundle\Entity\AbstractBase
     */
    public function populateEntities($values, $newClass, $parent, $arrayClass = null)
    {
        if ($parent && isset($values['idDel']) && $values['idDel']) {
            $entity = $this->getEntityManager()->getRepository($newClass)->findOneBy(array('id' => $values['idDel']));
            if (!$this->checkStatusTuple($entity)) {
                $this->getEntityManager()->remove($entity);
                return null;
            } else {
                $entity->setStatusTuple(0);
                if (!$this->debug) {
                    $this->getEntityManager()->persist($entity);
                }
                return $entity;
            }
        }

        if (!$parent) {
            $entity          = $newClass;
            $setParentMethod = null;
        } else {
            if (isset($values['id']) && $values['id']) {
                $entity = $this->getEntityManager()->getRepository($newClass)->findOneBy(array('id' => $values['id']));
                if (!$entity) {
                    echo '<pre>';
                    echo ">>>>>Este erro jamais deve acontecer. Revise o mapeamento<<<<\n";
                    echo '>>>>>Entidade não existente!!!!' . $entity . "<<<<\n";
                    echo '>>>>>' . $newClass . "<<<<\n";
                    print_r($values);
                    print_r($_POST);
                    die('Este erro jamais deve acontecer. Revise o mapeamento');
                };
                if (method_exists($entity, 'setTerm')) {
                    return $entity;
                }
            } else {
                $entity = new $newClass();
            }

            $class = explode('\\', get_class($parent));

            $class = $class[count($class) - 1];

            $setParentMethod = 'setId' . $class;
            try {
                $ref = new \ReflectionClass($entity);
            } catch (\Exception $e) {
                echo '<pre>';
                echo ">>>>>Este erro jamais deve acontecer. Revise o mapeamento<<<<\n";
                echo '>>>>>Entidade não existente!!!!' . $entity . "<<<<\n";
                echo '>>>>>' . $newClass . "<<<<\n";
                print_r($values);
                print_r($_POST);
                //             if ($this->debug)
                //                 print_r($this->rootEntity);
                die('Este erro jamais deve acontecer. Revise o mapeamento');
                throw new \Exception($entity . ">>>>" . $e->getMessage());
            }
            if (!method_exists($entity, $setParentMethod)) {
                $setParentMethod = 'set' . $class;
            }
            $strDoc = '';
            if (method_exists($entity, $setParentMethod)) {
                $strDoc = $ref->getMethod($setParentMethod)->getDocComment();
            }
            if (!strstr($strDoc, 'ArrayCollection')) {
                if (method_exists($entity, $setParentMethod)) {
                    $entity->$setParentMethod($parent);
                }
            }
        }

        try {
            $ref = new \ReflectionClass($entity);
        } catch (\Exception $e) {
            echo '<pre>';
            echo ">>>>>Este erro jamais deve acontecer. Revise o mapeamento<<<<\n";
            echo '>>>>>Entidade não existente!!!!' . $entity . "<<<<\n";
            echo '>>>>>' . $newClass . "<<<<\n";
            print_r($values);
            print_r($_POST);
            //             if ($this->debug)
            //                 print_r($this->rootEntity);
            die('Este erro jamais deve acontecer. Revise o mapeamento');
            throw new \Exception($entity . ">>>>" . $e->getMessage());
        }

        /**
         * Corrige o retorno de uma classe de proxy que impossibilita a leitura dos comentários corretos da classe
         */
        if (strstr($ref, "DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR")) {
            $bpos         = strpos($ref, 'extends ') + 8;
            $epos         = strpos($ref, ' implements') - $bpos;
            $correctClass = substr($ref, $bpos, $epos);
            $ref          = new \ReflectionClass($correctClass);
        }

        $methods = get_class_methods($entity);

        foreach ($methods as $method) {
            if ((('set' === substr($method, 0, 3) && strlen($method) > 3)) && $method != 'setStatusTuple' && $method != $setParentMethod) {

                $attr = lcfirst(substr($method, 3));
                if (is_array($values)) {
                    if (isset($values[$attr])) {
                        $value = $values[$attr];
                    } else {
                        $value = null;
                        continue;
                    }
                } else {
                    if ($values->query->has($attr)) {
                        $value = $values->query->get($attr);
                    } else if ($values->request->has($attr)) {
                        $value = $values->request->get($attr);
                    } else {
                        continue;
                    }
                }

                if (!is_array($value) && !is_object($value) && $value) {
                    $value = trim($value);
                }

                $params  = $ref->getMethod($method)->getParameters();
                $strDoc  = $ref->getMethod($method)->getDocComment();
                $strAttr = $ref->getProperty($attr)->getDocComment();
                $class   = '';

                if ($params[0]->getClass()) {
                    $class = $params[0]->getClass()->name;
                }

                if (strstr($strDoc, '\DateTime') || $class == 'DateTime') {
                    $class = '\DateTime';

                    if ($value) {
                        if (strstr($value, '/')) {
                            if (strstr($value, ':')) {
                                $value = $class::createFromFormat('d/m/Y h:m:i', $value);
                            } else {
                                $value = $class::createFromFormat('d/m/Y', $value);
                            }
                        } else {
                            if (strstr($value, ':')) {
                                $value = $class::createFromFormat('Y-m-d h:m:i', $value);
                            } else {
                                $value = $class::createFromFormat('Y-m-d', $value);
                            }
                        }
                    } else {
                        //corrige casos de strings vazias para datas
                        $value = null;
                    }
                } else if ((strstr($strDoc, 'ArrayCollection') && strstr($strDoc, '@innerEntity')) && 'set' === substr($method, 0, 3) && is_array($value)) {
                    $begin  = substr($strDoc, strpos($strDoc, '@innerEntity ') + 13);
                    $class  = substr($begin, 0, strpos($begin, "\n"));
                    $method = str_replace('set', 'add', $method);
                    $allInt = true;
                    foreach ($value as $key => $val) {
                        if (!is_int($key)) {
                            $allInt = false;
                        }
                    }
                    if ($allInt) {
                        foreach ($value as $key => $val) {
                            /**
                             * Tratamento para ManyToMany
                             */
                            if (strstr($strAttr, 'ManyToMany')) {

                                $begin    = substr($strDoc, strpos($strDoc, 'inverseJoinColumns={@ORM\JoinColumn(name="') + strlen('inverseJoinColumns={@ORM\JoinColumn(name="'));
                                $almost   = explode('_', substr($begin, 0, strpos($begin, "\",")));
                                $attrToId = '';
                                foreach ($almost as $vall) {
                                    $attrToId .= ucfirst($vall);
                                }
                                $attrToId       = lcfirst($attrToId);
                                $innerClassAttr = explode('\\', $class);
                                $innerClassAttr = lcfirst($innerClassAttr[count($innerClassAttr) - 1]);

                                if (isset($val[$attrToId])) {
                                    $val['id'] = $val[$attrToId];
                                }
                            }

                            $inner = $this->populateEntities($val, $class, $entity);
                            if ($inner) {
                                if (!$this->debug) {
                                    $this->getEntityManager()->persist($inner);
                                }
                                $entity->$method($inner);
                            }
                        }

                        continue;
                    }
                } else if ($class && !(strstr($strDoc, 'ArrayCollection') || $class == 'ArrayCollection') && 'set' === substr($method, 0, 3) && is_array($value) && !strstr($method, 'setId')) {
                    $value = $this->populateEntities($value, $class, $entity);
                } else if ($class && (strstr($method, 'setId') || strstr($strAttr, 'ManyToOne'))) {
                    // if (is_array($value) && count($value) > 1){var_dump($value);die;}

                    if (is_array($value) && array_key_exists('id', $value)) {
                        if ($value['id']) {
                            $value = $this->getEntityManager()->getRepository($class)->findOneBy(array('id' => $value['id']));
                        } else {
                            $value = null;
                        }

                    } else {
                        if ($value) {
                            $value = $this->getEntityManager()->getRepository($class)->findOneBy(array('id' => $value));
                        } else {
                            $value = null;
                        }

                    }

                } else if (strstr($strDoc, 'float') && $value) {
                    if (strstr($value, ',')) {
                        $value = (float) str_replace(',', '.', str_replace('.', '', $value));
                    }
                }

                $entity->$method($value);

                if (
                    is_object($value) &&
                    !strstr(get_class($value), 'DateTime') &&
                    !strstr(get_class($value), 'Doctrine') &&
                    !strstr($method, 'setId')
                ) {
                    if (!$this->debug) {
                        $this->getEntityManager()->persist($value);
                    }
                }
            }
        }

        return $entity;
    }

    /**
     * Regras de validação de dados da entidade
     *
     * @example Tipos de dados incompatíveis
     *
     * @throws NoImplementationException
     */
    public function validateRootEntity(Request $req)
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
    public function verifyRootEntity(Request $req)
    {
    }

    /**
     * Caso exista upload de arquivos, manipule-os aqui
     */
    public function handleUploads(Request $req)
    {
    }

    public function flushRootEntity(Request $req)
    {

        //         try {
        $this->setRootEntityForFlush($req);
        $this->populateRootEntity($req);
        $this->validateRootEntity($req);
        $this->verifyRootEntity($req);
        $this->handleUploads($req);
        $this->getEntityManager()->flush();
        //         } catch (\Exception $e) {
        //             throw new \Exception($e->getMessage());
        //         }
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
        if ($this->preSave($req)) {
            $this->flushRootEntity($req);
            $this->postSave($req);
        }
    }

    /**
     */
    public function postSave(Request $req)
    {
        return $req;
    }

    public function checkStatusTuple($entity)
    {
        return method_exists($entity, 'setStatusTuple') ? true : false;
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

        if (!$this->checkStatusTuple($this->getRootEntity())) {
            $this->getEntityManager()->remove($this->rootEntity);
        } else {
            if ($this->rootEntity->getStatusTuple() == 2) {
                throw new \Exception('Este registro não é removível!');
            }
            $this->rootEntity->setStatusTuple(0);
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
            $class           = $this->paginator;
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
            $class           = $this->validator;
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

    public function getSearchQuery(&$searchData)
    {
        $query = $this->getRootRepository()->createQueryBuilder('g')->getQuery();

        if ($searchData) {
            $and = ' where ';
            foreach ($searchData as $field => $criteria) {
                if (trim($searchData[$field]) != "") {
                    $query->setDQL($query->getDQL() . $and . 'g.' . $field . ' like :' . $field . ' ');
                    $query->setParameter($field, '%' . str_replace(' ', '%', $criteria) . '%');
                    $and = ' and ';
                } else {
                    unset($searchData[$field]);
                }
            }
        }

        return $query;
    }

    public function getSearchQueryGroupBy()
    {
        return ' group by g.id ';
    }

    public function searchQuery(Request $req)
    {
        $keys       = $req->query->keys();
        $searchData = array();
        foreach ($keys as $key) {
            if ($req->query->has($key)) {
                $searchData[$key] = $req->query->get($key);
            }
        }

        if (isset($searchData['sidx'])) {
            $orderby = $searchData['sidx'];
            $order   = $searchData['sord'];
        }

        unset($searchData['nd']);
        unset($searchData['rows']);
        unset($searchData['page']);
        unset($searchData['sidx']);
        unset($searchData['sord']);
        unset($searchData['
              d']);
        unset($searchData['_search']);

        $query = $this->getSearchQuery($searchData);

        $and = count($searchData) ? strstr($query->getDQL(), ' where') ? ' and' : ' ':' where';

        if ($this->checkStatusTuple($this->getRootEntity())) {
            if (!strstr($query->getDQL(), 'where')) {
                $and = ' where ';
            }
            $query->setDQL($query->getDQL() . $and . ' g.statusTuple <> 0');
            $and = ' and ';
        }

        $query->setDQL($query->getDQL() . $this->getSearchQueryGroupBy());

        if (isset($orderby)) {
            $query->setDQL($query->getDQL() . ' order by ' . $orderby . ' ' . $order);
        }

        return $query->setHydrationMode(Query::HYDRATE_ARRAY);
    }

    /**
     * Permite que outras entidades sejam consultadas para apresentação no grid de resposta
     *
     * @param integer $id
     * @return array
     */
    public function getSearchSubCells($id)
    {
        return array();
    }
}
