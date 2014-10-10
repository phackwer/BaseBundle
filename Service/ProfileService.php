<?php
namespace SanSIS\Core\BaseBundle\Service;

use \Doctrine\ORM\Query;
use \Symfony\Component\HttpFoundation\Request;
use \Doctrine\Common\Collections\ArrayCollection;

class ProfileService extends BaseService
{
    protected $rootEntityName = '\SanSIS\Core\BaseBundle\Entity\Profile';
    /**
     * Busca os dados para combos e outros campos do formulário
     * @return multitype:multitype:
     */
    public function getFormData($entityData = null)
    {
        $formData                           = array();
        $formData['functionality']          = array();
        
        $em = $this->getEntitymanager();
        
        //Funcionalidades
        $itens = $em->getRepository('SanSIS\Core\BaseBundle\Entity\Functionality')->findAll();
        foreach ($itens as $item){
            $formData['functionality'][$item->getId()] = $item->getTerm();
        }
        
        return $formData;
    }
    
}