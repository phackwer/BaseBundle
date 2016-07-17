<<<<<<< HEAD
<?php
namespace SanSIS\Core\BaseBundle\Service;

use \Doctrine\ORM\Query;
use \Symfony\Component\HttpFoundation\Request;
use \SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection;

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
=======
<?php

namespace SanSIS\Core\BaseBundle\Service;

class ProfileService extends BaseService
{
    protected $rootEntityName = '\SanSIS\Core\BaseBundle\Entity\Profile';

    public function getFormData($entityData = null)
    {
        $formData                    = array();
        $formData['functionalities'] = array();

        $itens = $this->entityManager->getRepository('SanSIS\Core\BaseBundle\Entity\Functionality')->findAll();
        foreach ($itens as $item) {
            $formData['functionalities'][$item->getId()] = $item->getTerm();
        }

        return $formData;
    }

    /**
     * Limpa as permissões anteriores para salvar as novas e já codifica as senhas
     * @param Request $req
     * @return Request
     */
    public function preSave(\Symfony\Component\HttpFoundation\Request $req)
    {
        //Limpa as permissões anteriores para salvar as novas
        if ($req->request->get('id')) {
            $ent = $this->getRootEntity($req->request->get('id'));
            foreach ($ent->getFunctionalities() as $func) {
                $ent->removeFunctionality($func);
            }
        }

        return $req;
    }
}
>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
