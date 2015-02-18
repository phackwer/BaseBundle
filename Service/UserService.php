<?php
namespace SanSIS\Core\BaseBundle\Service;

use \SanSIS\Core\BaseBundle\Entity\User;
use \Symfony\Component\HttpFoundation\Request;

class UserService extends BaseService
{
    protected $rootEntityName = '\SanSIS\Core\BaseBundle\Entity\LegalBody';

    protected $secFactory = null;

    protected $secContext = null;

    public function setSecFactory(\Symfony\Component\Security\Core\Encoder\EncoderFactory $secFactory)
    {
        $this->secFactory = $secFactory;
    }

    public function setSecContext(\Symfony\Component\Security\Core\SecurityContext $secContext)
    {
        $this->secContext = $secContext;
    }

    /**
     * Utiliza o método criado na Base Service para gerenciar os uploads
     */
    public function handleUploads(Request $req)
    {
        // $this->handleActorMainPhotoUpload($req);
    }

    /**
     * Sobrescreve o método para pesquisar por tipo de pessoa
     * @param array $searchData
     * @return Query
     */

    public function getSearchQuery(&$searchData)
    {
        $query = $this->getRootRepository()
                      ->createQueryBuilder('g')
                      ->Join('g.person', 'p')
                      ->Join('p.user', 'u')
                      ->getQuery();

        if ($searchData) {
            $and = ' where ';
            if (isset($searchData['name'])) {
                $query->setDQL($query->getDQL() . $and . 'g.name like :name');
                $query->setParameter(':name', '%' . str_replace(' ', '%', $searchData['name']) . '%');
                $and = ' and ';
            }
            if ($searchData['isActive'] == 1 || $searchData['isActive'] === '0') {
                $query->setDQL($query->getDQL() . $and . 'u.isActive = :isActive');
                $query->setParameter(':isActive', $searchData['isActive']);
                $and = ' and ';
            }
        }

        return $query;
    }

    /**
     * Permite que outras entidades sejam consultadas para apresentação no grid de resposta
     *
     * @param integer $id
     * @return array
     */
    public function getSearchSubCells($id)
    {
        $person = $this->getEntityManager()->getRepository('SanSIS\Core\BaseBundle\Entity\LegalBodyPerson')->findOneBy(array('idLegalBody' => $id));

        $arr = array();

        $arr['isActive'] = '';
        if ($person) {
            $user = $this->getEntityManager()->getRepository('SanSIS\Core\BaseBundle\Entity\User')->findOneBy(array('idLegalBodyPerson' => $person->getId()));
            $arr['isActive'] .= $user->getIsActive() ? '<span style="color:#009900">Ativa</span>' : '<span style="color:#FF0000">Inativa</span>';
        }

        return $arr;
    }

    /**
     * Limpa as permissões anteriores para salvar as novas e já codifica as senhas
     * @param Request $req
     * @return Request
     */
    public function preSave(Request $req)
    {
//         echo '<pre>';
        //         var_dump($_FILES);die;
        //As validações das senhas vieram para a PreSave pois eu preciso
        //comparar o que está no banco com o que foi submetido.
        //O populate já carrega e coloca o no EntityManager
        //o que causa problemas para comparação. O correto semanticamente
        //seria que este trecho de código estivesse no verify.

        //Evita que a senha seja zerada acidentalmente
        $person = $req->request->get('person');
        if ($req->request->get('id')) {
            $user = $this->getEntityManager()->getRepository('\SanSIS\Core\BaseBundle\Entity\User')->findOneBy(array('username' => $person['user']['username']));
        } else {
            $user = new User();
        }

        if (isset($person['user']['password']) && !trim($person['user']['password'])) {
            $person['user']['password'] = $user->getPassword();
        }
        //verifica se a senha informada é válida
        else if (isset($person['user']['password']) && trim($person['user']['password'])) {
            if (($person['user']['password'] == $person['user']['confirmPassword'])) {
                $encoder                           = $this->secFactory->getEncoder($user);
                $person['user']['confirmPassword'] = $person['user']['password'] = $encoder->encodePassword($person['user']['password'], $user->getSalt());
            } else {
                unset($person['user']['password']);
                $req->request->set('person', $person);
                //populate forçado para redirect correto do formulário
                $this->populateRootEntity($req);
                throw new \Exception('Senhas informadas inválidas');
            }
        }

        $req->request->set('person', $person);

        //Limpa as permissões anteriores para salvar as novas
        if ($req->request->get('id')) {
            $ent      = $this->getRootEntity($req->request->get('id'));
            $personDb = $ent->getPerson();
            if ($personDb) {
                $profrels = $personDb->getProfessionalRelation();
                foreach ($profrels as $profrel) {
                    foreach ($profrel->getProfile() as $profile) {
                        $profrel->removeProfile($profile);
                    }
                }
            }

            $actor = $ent->getActor();
            if ($actor) {
                foreach ($actor->getRole() as $role) {
                    $role->removeActor($actor);
                }
            }
        }

        return $req;
    }

    /**
     * Verificações de regras contra o banco de dados
     */
    public function verifyRootEntity(Request $req)
    {
        $person = $req->request->get('person');

        if (!$this->checkUniqueCpf($req->request->get('id'), $person['cpf'])) {
            throw new \Exception('CPF deve ser único.');
        }

        if (!$this->checkUniqueUsername($req->request->get('id'), $person['user']['username'])) {
            throw new \Exception('Login deve ser único.');
        }
    }

    /**
     * Busca os dados para combos e outros campos do formulário
     * @return multitype:multitype:
     */
    public function getFormData($entityData = null)
    {
        $formData                          = array();
        $formData['profile']               = array();
        $formData['legalBodyRelationType'] = array();
        $formData['language']              = array();
        $formData['country']               = array();
        $formData['stateProvince']         = array();
        $formData['city']                  = array();
        $formData['idEntity']              = array();

        $em = $this->getEntitymanager();

        //Tipo de vínculo
        $itens = $em->getRepository('SanSIS\Core\BaseBundle\Entity\LegalBodyRelationType')->findAll();
        foreach ($itens as $item) {
            $formData['legalBodyRelationType'][$item->getId()] = $item->getTerm();
        }

        //Perfis
        $itens = $em->getRepository('SanSIS\Core\BaseBundle\Entity\Profile')->findAll();
        foreach ($itens as $item) {
            $formData['profile'][$item->getId()] = $item->getTerm();
        }
        ksort($formData['profile']);

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

        //Entidade proprietária da aplicação
        $itens = $em->getRepository('SanSIS\Core\BaseBundle\Entity\SystemData')->findAll();
        foreach ($itens as $item) {
            $formData['idEntity'][0]         = array();
            $formData['idEntity'][0]['id']   = $item->getIdOrganization()->getId();
            $formData['idEntity'][0]['name'] = $item->getIdOrganization()->getIdLegalBody()->getName();
        }

        return $formData;
    }

    public function accountSave()
    {
        //Checar se está tentando salvar para senha de outro
    }

    public function getUserData()
    {
        $user = $this->secContext
                     ->getToken()
                     ->getUser();

        return $user->toArray();
    }

    public function getAccountFormData()
    {
        return array();
    }

}
