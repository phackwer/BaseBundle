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
