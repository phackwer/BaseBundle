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

        $this->setRootEntity($user);

        $encoder = $this->secFactory->getEncoder($user);

        $newPwd = $encoder->encodePassword($req->request->get('newPassword'), $user->getSalt());
        $cnfPwd = $req->request->get('confirmPassword');

        if ($req->request->get('newPassword')) {
            $req->request->set('password', $newPwd);
        }
        return $req;
    }

    public function verifyRootEntity(Request $req)
    {
        //Pega usuário atualmente logado
        $user = $this->secContext
                     ->getToken()
                     ->getUser();

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
                throw new \Exception('Nova senha e confirmação diferem. Não é possível atualizar a senha.');
            } else if ($newPwd && $cnfPwd && ($newPwd != trim($newPwd))) {
                throw new \Exception('Senhas não podem conter espaço em branco no começo ou final');
            }
        } else {
            throw new \Exception('Senha atual inválida. Verifique se o Caps Lock está pressionado ou tente novamente.');
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
