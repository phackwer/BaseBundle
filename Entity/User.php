<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="core.system_user")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class User extends AbstractBase implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=256, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=256, nullable=true)
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_active", type="integer", nullable=false)
     */
    private $isActive = '1';

    /**
     * @var \LegalBodyPerson
     *
     * @ORM\OneToOne(targetEntity="LegalBodyPerson", inversedBy="user")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_legal_body_person", referencedColumnName="id")
     * })
     */
    private $idLegalBodyPerson;

    /**
     * I wanna be sedated
     *
     * @var Ramones
     */
    private $salt = "Hey!Ho!Let'sGo!";

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set isActive
     *
     * @param integer $isActive
     * @return LegalBody
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return integer
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set idLegalBodyPerson
     *
     * @param \SanSIS\Core\BaseBundle\Entity\LegalBodyPerson $idLegalBodyPerson
     * @return User
     */
    public function setIdLegalBodyPerson(\SanSIS\Core\BaseBundle\Entity\LegalBodyPerson $idLegalBodyPerson = null)
    {
        $this->idLegalBodyPerson = $idLegalBodyPerson;

        return $this;
    }

    /**
     * Get idLegalBodyPerson
     *
     * @return \SanSIS\Core\BaseBundle\Entity\LegalBodyPerson
     */
    public function getIdLegalBodyPerson()
    {
        return $this->idLegalBodyPerson;
    }

    public function getRoles()
    {
        $roles = array();

        $person  = $this->getIdLegalBodyPerson();
        $prorels = $person->getProfessionalRelation();
        if($prorels){
            foreach ($prorels as $propel){
                $profiles = $propel->getProfile();
                if ($profiles) {
                    foreach ($profiles as $profile){
                        $funcs = $profile->getFunctionalities();
                        if ($funcs) {
                            foreach ($funcs as $func){
                                $roles[] = $func->getRoleWord();
                            }
                        }
                    }
                }
            }
        }

        return $roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt(){
        return '';
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(){

    }
}
