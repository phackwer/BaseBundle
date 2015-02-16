<?php
namespace SanSIS\Core\BaseBundle\Service;

use \Doctrine\ORM\Query;
use \Symfony\Component\HttpFoundation\Request;

class EntityService extends BaseService
{
    protected $rootEntityName = '\SanSIS\Core\BaseBundle\Entity\LegalBody';

    /**
     * Utiliza o método criado na Base Service para gerenciar os uploads
     */
    public function handleUploads(Request $req)
    {
        // $this->handleActorMainPhotoUpload($req);
    }

    /**
     * Sobrescreve o método para pesquisar por tipo de pessoa e pseudônimos
     * @param array $searchData
     * @return Query
     */
    public function getSearchQuery(&$searchData)
    {
        $query = $this->getRootRepository()
                      ->createQueryBuilder('g')
                      // ->LeftJoin('g.actor', 'a')
                      // ->LeftJoin('a.pseudonym', 'p')
                      ->getQuery();

        if ($searchData) {
            $and = ' where ';
            if (isset($searchData['name'])) {
                $query->setDQL($query->getDQL() . $and . 'g.name like :name');
                // $and = ' or ';
                // $query->setDQL($query->getDQL() . $and . 'p.pseudonym like :name )');
                $query->setParameter(':name', '%' . str_replace(' ', '%', $searchData['name']) . '%');
                $and = ' and ';
            }
            if (isset($searchData['idLegalBodyType']) && $searchData['idLegalBodyType']) {
                $query->setDQL($query->getDQL() . $and . 'g.idLegalBodyType = :id');
                $query->setParameter(':id', $searchData['idLegalBodyType']);
                $and = ' and ';
            }
        }

        return $query;
    }

    // /**
    //  * Permite que outras entidades sejam consultadas para apresentação no grid de resposta
    //  *
    //  * @param integer $id
    //  * @return array
    //  */
    // public function getSearchSubCells($id)
    // {
    //     $actor = $this->getEntityManager()->getRepository('SanSIS\Core\BaseBundle\Entity\Actor')->findOneBy(array('idLegalBody' => $id));

    //     $arr = array();

    //     // $arr['pseudonym'] = $virgula = '';
    //     // if ($actor) {
    //     //     foreach ($actor->getPseudonym() as $p) {
    //     //         $arr['pseudonym'] .= $virgula . $p->getPseudonym();
    //     //         $virgula = ', ';
    //     //     }
    //     // }

    //     return $arr;
    // }

    /**
     * Remove atributos que não devem ser incluídos de acordo com o tipo da entidade
     * @param Request $req
     * @return Request
     */

    public function preSave(Request $req)
    {
        //filtros para quando entidade já existe no banco de dados
        if ($req->request->get('id')) {
            $ent  = $this->getRootEntity($req->request->get('id'));
            $type = $ent->getIdLegalBodyType()->getId();
            $req->request->set('idLegalBodyType', $type);

            $actor = $ent->getActor();
            if ($actor) {
                $roles = $actor->getRole();
                foreach ($roles as $role) {
                    if ($role->getId() != 100) {
                        $jns = $this->getEntityManager()
                                    ->getRepository('SanSIS\Core\BaseBundle\Entity\JnActorRole')
                                    ->findBy(
                                        array(
                                            'idRole'  => $role->getId(),
                                            'idActor' => $actor->getId(),
                                        ));

                        foreach ($jns as $jn) {
                            $this->getEntityManager()->remove($jn);
                        }
                    }
                }

                $this->getEntityManager()->flush();
                $this->getEntityManager()->refresh($ent);
            }
        } else {
            $type = $req->request->get('idLegalBodyType');
        }

        $actor = $req->request->get('actor');

        foreach ($actor['pseudonym'] as $key => $value) {
            if (isset($value['pseudonym']) && !trim($value['pseudonym'])) {
                if (!isset($value['idDel']) && !($value['id'])) {
                    unset($actor['pseudonym'][$key]);
                } else if ($value['id']) {
                    $actor['pseudonym'][$key]['idDel'] = $value['id'];
                    unset($actor['pseudonym'][$key]['id']);
                } else {
                    unset($actor['pseudonym'][$key]);
                }
            }
        }

        foreach ($actor['biography'] as $key => $value) {
            if (isset($value['biography']) && !trim($value['biography'])) {
                if (!isset($value['idDel']) && !($value['id'])) {
                    unset($actor['biography'][$key]);
                } else if ($value['id']) {
                    $actor['biography'][$key]['idDel'] = $value['id'];
                    unset($actor['biography'][$key]['id']);
                } else {
                    unset($actor['biography'][$key]);
                }
            }
        }

        foreach ($actor['bibliography'] as $key => $value) {
            if (isset($value['data']) && !trim($value['data'])) {
                if (!isset($value['idDel']) && !($value['id'])) {
                    unset($actor['bibliography'][$key]);
                } else if ($value['id']) {
                    $actor['bibliography'][$key]['idDel'] = $value['id'];
                    unset($actor['bibliography'][$key]['id']);
                } else {
                    unset($actor['bibliography'][$key]);
                }
            }
        }

        //remove itens de acordo com o tipo da pessoa para não popular o banco desnecessariamente
        if ($type == 1) {
            $req->request->set('organization', null);
            if (isset($actor['rolePF'])) {
                $actor['role'] = $actor['rolePF'];
                unset($actor['rolePF']);
            }
        } else if ($type == 2) {
            $req->request->set('person', null);
            if (isset($actor['rolePJ'])) {
                $actor['role'] = $actor['rolePJ'];
                unset($actor['rolePJ']);
            }
        } else if ($type == 3) {
            $req->request->set('person', null);
            if (isset($actor['roleFM'])) {
                $actor['role'] = $actor['roleFM'];
                unset($actor['roleFM']);
            }
        }

        $req->request->set('actor', $actor);

        return $req;
    }

    public function validateRootEntity(Request $req)
    {
        $early = $this->getRootEntity()->getEarliestDate();
        $late  = $this->getRootEntity()->getLatestDate();

        if ($early && $late) {
            $interval = $early->diff($late);
            $diff     = (int) $interval->format('%R%a');
            if ($diff < 0) {
                throw new \Exception('Datas inválidas. Corrija, por favor.');
            }
        }
    }

    /**
     * Busca os dados para combos e outros campos do formulário
     * @return multitype:multitype:
     */
    public function getFormData($entityData = null)
    {
        $formData                          = array();
        $formData['legalBodyType']         = array();
        $formData['legalBodyRelationType'] = array();
        $formData['rolesPF']               = array();
        $formData['rolesPJ']               = array();
        $formData['rolesFM']               = array();
        $formData['language']              = array();
        $formData['country']               = array();
        $formData['stateProvince']         = array();
        $formData['city']                  = array();

        $em = $this->getEntitymanager();

        //Tipo
        $itens = $em->getRepository('SanSIS\Core\BaseBundle\Entity\LegalBodyType')->findAll();
        foreach ($itens as $item) {
            $formData['legalBodyType'][$item->getId()] = $item->getTerm();
        }

        //Tipo de vínculo
        $itens = $em->getRepository('SanSIS\Core\BaseBundle\Entity\LegalBodyRelationType')->findAll();
        foreach ($itens as $item) {
            $formData['legalBodyRelationType'][$item->getId()] = $item->getTerm();
        }

        //Roles
        $itens = $em->getRepository('SanSIS\Core\BaseBundle\Entity\Role')->findBy(
            array('statusTuple' => array(1, 2))
        );
        foreach ($itens as $item) {
            if ($item->getHuman() == 1) {
                $formData['rolesPF'][$item->getId()] = $formData['rolesPJ'][$item->getId()] = $formData['rolesFM'][$item->getId()] = $item->getTerm();
            } else if ($item->getHuman() == 2) {
                $formData['rolesPF'][$item->getId()] = $item->getTerm();
            } else if ($item->getHuman() == 3) {
                $formData['rolesPJ'][$item->getId()] = $item->getTerm();
            } else if ($item->getHuman() == 4) {
                $formData['rolesFM'][$item->getId()] = $item->getTerm();
            }

        }

        //Países
        $itens = $em->getRepository('SanSIS\Core\BaseBundle\Entity\Country')->findBy(
            array('statusTuple' => array(1, 2))
        );
        foreach ($itens as $item) {
            $formData['country'][$item->getId()] = $item->getTerm();
        }

        //Estados e províncias
        $itens = $em->getRepository('SanSIS\Core\BaseBundle\Entity\StateProvince')->findBy(
            array(
                'statusTuple' => array(1, 2),
                'idCountry'   => isset($entityData['idCountry']['id']) ? $entityData['idCountry']['id'] : null,
            )
        );
        foreach ($itens as $item) {
            $formData['stateProvince'][$item->getId()] = $item->getTerm();
        }

        //Cidades
        $itens = $em->getRepository('SanSIS\Core\BaseBundle\Entity\City')->findBy(
            array(
                'statusTuple'     => array(1, 2),
                'idStateProvince' => isset($entityData['idStateProvince']['id']) ? $entityData['idStateProvince']['id'] : null,
            )
        );
        foreach ($itens as $item) {
            $formData['city'][$item->getId()] = $item->getTerm();
        }

        return $formData;
    }

}
