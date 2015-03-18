<?php
namespace SanSIS\Core\BaseBundle\Service;

use \Symfony\Component\HttpFoundation\Request;

class AccountService extends UserService
{
    protected $rootEntityName = '\SanSIS\Core\BaseBundle\Entity\User';

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

    public function preSave(Request $req)
    {
        //Pega usuário atualmente logado
        $user = $this->secContext
                     ->getToken()
                     ->getUser();

        $this->getRootEntity($user->getId());

        return $req;
    }

    public function verifyRootEntity(Request $req)
    {
//        $sql = 'select * from sandbox.core_system_user';
//        $stmt = $this->getEntityManager()
//            ->getConnection()
//            ->prepare($sql);
//        $stmt->execute();
//        $users = $stmt->fetchAll();
//        var_dump($users);die;

        //Pega usuário atualmente logado
        $user = $this->secContext
                     ->getToken()
                     ->getUser();

//        echo $user->getPassword();die;

        $encoder = $this->secFactory->getEncoder($user);

        //senha que está no banco
        $cdbPwd = $user->getPassword();
        //senha  atual para verificação com a do banco
        $chkPwd = $encoder->encodePassword($req->request->get('currentPassword'), $user->getSalt());
        $newPwd = $req->request->get('newPassword');
        $cnfPwd = $req->request->get('confirmPassword');

        //verifica se a senha informada é válida
        if ($cdbPwd === $chkPwd) {
            if ($newPwd !== $cnfPwd) {
                MessageService::addMessage('error', 'Nova senha e confirmação diferem. Não é possível atualizar a senha.');
                throw new \Exception('');
            } else if ($newPwd && $cnfPwd && ($newPwd != trim($newPwd))) {
                MessageService::addMessage('error', 'Senhas não podem conter espaço em branco no começo ou final');
                throw new \Exception('');
            }
            //Ok, atualiza a senha do usuário com o hash pois está tudo ok - Obs
            $this->getRootEntity()->setPassword($encoder->encodePassword($req->request->get('newPassword'), $user->getSalt()));
        } else {
            MessageService::addMessage('error', $cdbPwd."\n".$chkPwd.'Senha atual inválida. Verifique se o Caps Lock está pressionado ou tente novamente.');
            throw new \Exception('');
        }
    }

    public function postSave(Request $req)
    {
        $this->secContext
             ->getToken()
             ->getUser()
             ->setPassword($this->getRootEntity()->getPassword());
    }

}
