<?php
namespace SanSIS\Core\BaseBundle\Service;

use \Doctrine\ORM\Query;
use \Symfony\Component\HttpFoundation\Request;
use \SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection;

class EntityService extends BaseService
{
    protected $rootEntityName = '\SanSIS\Core\BaseBundle\Entity\LegalBody';
    
    /**
     * Sobrescreve o método para pesquisar por tipo de pessoa e pseudônimos
     * @param array $searchData
     * @return Query
     */
    function getSearchQuery($searchData)
    {
        $query = $this->getRootRepository()
                      ->createQueryBuilder('g')
                      ->getQuery();
        
        if ($searchData) {
            $and = ' where ';
            if (isset($searchData['name'])) {
                $query->setDQL($query->getDQL() . $and . '( g.name like :name');
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
    
    /**
     * Remove atributos que não devem ser incluídos de acordo com o tipo da entidade
     * @param Request $req
     * @return Request
     */
    
    public function preSave(Request $req)
    {        
        //filtros para quando entidade já existe no banco de dados
        if ($req->request->get('id')) {
            $ent = $this->getRootEntity($req->request->get('id'));
            $type = $ent->getIdLegalBodyType()->getId();
            $req->request->set('idLegalBodyType', $type);
        }
        else {
            $type = $req->request->get('idLegalBodyType');
        }
        
        
        return $req;
    }
    
    public function validateRootEntity(Request $req){
        $early = $this->getRootEntity()->getEarliestDate();
        $late  = $this->getRootEntity()->getLatestDate();
        
        if($early && $late){
            $interval = $early->diff($late);
            $diff = (int)$interval->format('%R%a');
            if ($diff < 0){
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
        $formData                           = array();
        $formData['legalBodyType']          = array();
        $formData['legalBodyRelationType']  = array();
        $formData['rolesPF']                = array();
        $formData['rolesPJ']                = array();
        $formData['rolesFM']                = array();
        $formData['language']               = array();
        $formData['country']                = array();
        $formData['stateProvince']          = array();
        $formData['city']                   = array();
        
        $em = $this->getEntitymanager();
        
        //Tipo 
        $itens = $em->getRepository('SanSIS\Core\BaseBundle\Entity\LegalBodyType')->findAll();
        foreach ($itens as $item){
            $formData['legalBodyType'][$item->getId()] = $item->getTerm();
        }
        
        //Tipo de vínculo
        $itens = $em->getRepository('SanSIS\Core\BaseBundle\Entity\LegalBodyRelationType')->findAll();
        foreach ($itens as $item){
            $formData['legalBodyRelationType'][$item->getId()] = $item->getTerm();
        }
        
        //Países
        $itens = $em->getRepository('SanSIS\Core\BaseBundle\Entity\Country')->findBy(
            array('statusTuple' => array(1,2))
        );
        foreach ($itens as $item){
            $formData['country'][$item->getId()] = $item->getTerm();
        }
        
        //Estados e províncias
        $itens = $em->getRepository('SanSIS\Core\BaseBundle\Entity\StateProvince')->findBy(
            array(
                'statusTuple' => array(1,2), 
                'idCountry' => isset($entityData['idCountry']['id']) ? $entityData['idCountry']['id'] : null 
            )
        );
        foreach ($itens as $item){
            $formData['stateProvince'][$item->getId()] = $item->getTerm();
        }
        
        //Cidades
        $itens = $em->getRepository('SanSIS\Core\BaseBundle\Entity\City')->findBy(
            array(
                'statusTuple' => array(1,2), 
                'idStateProvince' => isset($entityData['idStateProvince']['id']) ? $entityData['idStateProvince']['id'] : null
            )
        );
        foreach ($itens as $item){
            $formData['city'][$item->getId()] = $item->getTerm();
        }
        
        return $formData;
    }
    
}