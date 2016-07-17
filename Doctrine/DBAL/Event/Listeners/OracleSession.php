<<<<<<< HEAD
<?php 

namespace SanSIS\Core\BaseBundle\Doctrine\DBAL\Event\Listeners;

use Doctrine\DBAL\Event\ConnectionEventArgs;

class OracleSession
{
    protected $role = null;
    protected $rolePwd = null;

    public function preSave(ConnectionEventArgs $args)
    {
        $this->setConnectionRole($args);
    }

    public function preInsert(ConnectionEventArgs $args)
    {
        $this->setConnectionRole($args);
    }

    public function preUpdate(ConnectionEventArgs $args)
    {
        $this->setConnectionRole($args);
    }

    public function preDelete(ConnectionEventArgs $args)
    {
        $this->setConnectionRole($args);
    }

    public function postConnect(ConnectionEventArgs $args)
    {
        $this->setConnectionRole($args);
    }

    public function setConnectionRole(ConnectionEventArgs $args)
    {
        $conn = $args->getConnection();
        $conn->executeUpdate('ALTER SESSION SET NLS_DATE_FORMAT=\'YYYY-MM-DD HH24:MI:SS\'');
        $conn->executeUpdate('ALTER SESSION SET NLS_COMP=\'LINGUISTIC\'');
//        $conn->executeUpdate('ALTER SESSION SET NLS_SORT=\'BINARY_AI\'');

        if ($this->role && $this->rolePwd){
            $conn->executeUpdate('SET ROLE '.$this->role.' IDENTIFIED BY '.$this->rolePwd);
        }
    }

    public function setRole($role = null)
    {
        if ($role) {
            $this->role = $role;
        }
    }

    public function setRolePwd($rolePwd = null)
    {
        if ($rolePwd) {
            $this->rolePwd = $rolePwd;
        }
    }
=======
<?php 

namespace SanSIS\Core\BaseBundle\Doctrine\DBAL\Event\Listeners;

use Doctrine\DBAL\Event\ConnectionEventArgs;

class OracleSession
{
    protected $role = null;
    protected $rolePwd = null;

    public function preSave(ConnectionEventArgs $args)
    {
        $this->setConnectionRole($args);
    }

    public function preInsert(ConnectionEventArgs $args)
    {
        $this->setConnectionRole($args);
    }

    public function preUpdate(ConnectionEventArgs $args)
    {
        $this->setConnectionRole($args);
    }

    public function preDelete(ConnectionEventArgs $args)
    {
        $this->setConnectionRole($args);
    }

    public function postConnect(ConnectionEventArgs $args)
    {
        $this->setConnectionRole($args);
    }

    public function setConnectionRole(ConnectionEventArgs $args)
    {
        $conn = $args->getConnection();
        $conn->executeUpdate('ALTER SESSION SET NLS_DATE_FORMAT=\'YYYY-MM-DD HH24:MI:SS\'');
        $conn->executeUpdate('ALTER SESSION SET NLS_COMP=\'LINGUISTIC\'');
//        $conn->executeUpdate('ALTER SESSION SET NLS_SORT=\'BINARY_AI\'');

        if ($this->role && $this->rolePwd){
            $conn->executeUpdate('SET ROLE '.$this->role.' IDENTIFIED BY '.$this->rolePwd);
        }
    }

    public function setRole($role = null)
    {
        if ($role) {
            $this->role = $role;
        }
    }

    public function setRolePwd($rolePwd = null)
    {
        if ($rolePwd) {
            $this->rolePwd = $rolePwd;
        }
    }
>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
}