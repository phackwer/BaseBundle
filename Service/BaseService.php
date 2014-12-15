<?php
namespace SanSIS\Core\BaseBundle\Service;

use SanSIS\Core\BaseBundle\Service\EntityServiceAbstract;
use \Doctrine\ORM\Query;
use \Symfony\Component\HttpFoundation\Request;

class BaseService extends EntityServiceAbstract
{
    
    public function getEntityData()
    {
        $formData             = array();
        $formData['entity']   = array();
        
        $itens = $this->entityManager->getRepository('SanSIS\Core\BaseBundle\Entity\SystemData')->findAll();
        foreach ($itens as $item){
            $formData['entity'] = $item->getIdOrganization()->getIdLegalBody()->getName();
        }
        
        return $formData;
    }
    
    public function getStatesProvincesByCountry($id=null) 
    {
        $spEntity = $this->entityManager->getRepository('SanSIS\Core\BaseBundle\Entity\StateProvince')->findBy(
            array(
                'idCountry' => $id,
                'statusTuple' => array(1,2)
            ), array('term' => 'ASC'));
        
        $sps = array();
        if(!empty($id)) {
            foreach($spEntity as $key => $value) {
                $sps[$key]['id'] = $value->getId();
                $sps[$key]['term'] = $value->getTerm();
            }
        }
        
        return $sps;
    }
    
    public function getCitiesByStateProvince($id=null) 
    {
        $citiesEntity = $this->entityManager->getRepository('SanSIS\Core\BaseBundle\Entity\City')->findBy(
            array(
                'idStateProvince' => $id,
                'statusTuple' => array(1,2)
            ), array('term' => 'ASC'));
        
        $cities = array();
        if(!empty($id)) {
            foreach($citiesEntity as $key => $value) {
                $cities[$key]['id'] = $value->getId();
                $cities[$key]['term'] = $value->getTerm();
            }
        }
        
        return $cities;
    }
    
    public function getMeasureUnitiesByType($id=null) 
    {
        $query = $this->entityManager->getRepository('SanSIS\Core\BaseBundle\Entity\MeasureUnity')->createQueryBuilder('g');
        
        $query->innerJoin('g.type', 't');
        
        $query = $query->getQuery();
        
        $query->setDQL($query->getDQL() . ' where t.id = :idType');
        $query->setParameter(':idType', $id);
        
        $full = $query->getArrayResult();
        
        $entities = array();
        if(!empty($id)) {
            foreach($full as $key => $value) {
                $entities[$key]['id']   = $value['id'];
                $entities[$key]['term'] = $value['term'];
            }
        }
        
        return $entities;
    }
    
    public function getOrganizationByName($name=null, $except){
        return $this->getEntityByName($name, 2, $except);
    }
    
    public function getEntityByName($name=null, $type=null, $except=null) 
    {
        $query = $this->entityManager->getRepository('SanSIS\Core\BaseBundle\Entity\LegalBody')
                                     ->createQueryBuilder('g')
                                     ->select('g.id', 'g.name', 'p.pseudonym')
                                     ->LeftJoin('g.actor', 'a')
                                     ->LeftJoin('a.pseudonym', 'p')
                                     ->getQuery();
        
        $and = ' where ';
        
        if ($name) {
            $query->setDQL($query->getDQL() . $and . '( g.name like :name');
            $and = ' or ';
            $query->setDQL($query->getDQL() . $and . 'p.pseudonym like :name )');
            $query->setParameter(':name', '%' . str_replace(' ', '%', $name) . '%');
            $and = ' and ';
        }
        
        if ($except) {
                $query->setDQL($query->getDQL() . $and . 'g.id' . ' <> :id ');
                $query->setParameter(':id', $except);
                $and = ' and ';
        }
        
        
        if ($type) {
            if (is_array($type)) {
                $query->setDQL($query->getDQL().$and.' ( ');
                $or = '';
                foreach ($type as $k=>$value) {
                    $query->setDQL($query->getDQL() . $or . 'g.idLegalBodyType = :type'.$k);
                    $query->setParameter(':type'.$k, $value);
                    $or = ' or ';
                }
                $query->setDQL($query->getDQL().' ) ');
            }
            else {
                $query->setDQL($query->getDQL() . $and . 'g.idLegalBodyType = :type');
                $query->setParameter(':type', $type);
                $and = ' and ';
            }
        }
        $query->setDQL($query->getDQL() . $and.' g.statusTuple' . ' <> 0 ');
        
        $query->setDQL($query->getDQL() .' order by g.name');
        
        $full = $query->getArrayResult();
        
        $entities = array();
        if(!empty($name)) {
            foreach($full as $key => $value) {
                if ($value['pseudonym']) {
                    if (isset($entities[$value['id']]['name'])){
                        $value['name'] = str_replace(')', ', '.$value['pseudonym'].')', $entities[$value['id']]['name']);
                    } else {
                        $value['name'] .= ' ('.$value['pseudonym'].')';
                    }
                    
                }
                $entities[$value['id']]['id']   = $value['id'];
                $entities[$value['id']]['name'] = $value['name'];
            }
        }
        
        return $entities;
    }
    
    /**
     * CPF deve ser único
     */
    public function checkUniqueCpf($id, $cpf)
    {
        $query = $this->entityManager->getRepository('SanSIS\Core\BaseBundle\Entity\LegalBody')
                ->createQueryBuilder('g')
                ->select('g.id')
                ->LeftJoin('g.person', 'p')
                ->where('p.cpf = :cpf')
                ->setParameter('cpf', $cpf)
                ->andWhere('g.statusTuple in (:statusTuple)')
                ->setParameter('statusTuple', '1,2')
                ->getQuery();
        
        $cpfs = $query->getArrayResult();
        
        if (count($cpfs) > 0){
            foreach ($cpfs as $cpf){
                if ($cpf['id'] != $id) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Username deve ser único
     */
    public function checkUniqueUsername($id, $username)
    {
        $query = $this->entityManager->getRepository('SanSIS\Core\BaseBundle\Entity\LegalBody')
        ->createQueryBuilder('g')
        ->select('g.id')
        ->LeftJoin('g.person', 'p')
        ->LeftJoin('p.user', 'u')
        ->where('u.username = :username')
        ->setParameter('username', $username)
        ->andWhere('g.statusTuple in (1,2)')
        ->getQuery();
        
        $users = $query->getArrayResult();
        
        if (count($users) > 0){
            foreach ($users as $user){
                if ($user['id'] != $id) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Número de Registro deve ser único
     */
    public function checkUniqueInventoryNumber($id, $inventoryNumber)
    {
        $objs = $this->getEntityManager()->getRepository('\SanSIS\Core\BaseBundle\Entity\Object')->findBy(array('inventoryNumber' => $inventoryNumber));
        if (count($objs) > 0){
            foreach ($objs as $obj){
                if ($obj->getId() != $id) {
                    return false;
                }
            }
        }
        return true;
    }
}