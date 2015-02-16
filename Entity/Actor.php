<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection;

/**
 * Actor
 *
 * @ORM\Table(name="core.actor")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class Actor extends AbstractBase
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
     * @var \LegalBody
     *
     * @ORM\ManyToOne(targetEntity="LegalBody")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_legal_body", referencedColumnName="id")
     * })
     */
    private $idLegalBody;

    /**
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinTable(name="core.jn_actor_role",
     *      joinColumns={@ORM\JoinColumn(name="id_actor", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_role", referencedColumnName="id")}
     *      )
     */
    private $role;

    /**
     * Define as collections
     */
    public function __construct(){
        $this->role = new ArrayCollection();
    }

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
     * Set idLegalBody
     *
     * @param \SanSIS\Core\BaseBundle\Entity\LegalBody $idLegalBody
     * @return Actor
     */
    public function setIdLegalBody(\SanSIS\Core\BaseBundle\Entity\LegalBody $idLegalBody = null)
    {
        $this->idLegalBody = $idLegalBody;

        return $this;
    }

    /**
     * Get idLegalBody
     *
     * @return \SanSIS\Core\BaseBundle\Entity\LegalBody
     */
    public function getIdLegalBody()
    {
        return $this->idLegalBody;
    }

    /**
     * Set pseudonym
     *
     * @param \SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection $pseudonym
     * @innerEntity \SanSIS\Core\BaseBundle\Entity\ActorPseudonym
     * @return ActorPseudonym
     */
    public function setPseudonym(\SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection $pseudonym = null)
    {
        $this->pseudonym = $pseudonym;

        return $this;
    }

    /**
     * Set role
     *
     * @param \SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection $role
     * @innerEntity \SanSIS\Core\BaseBundle\Entity\Role
     * @return Role
     */
    public function setRole(\SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Add role
     *
     * @param \SanSIS\Core\BaseBundle\Entity\Role $role
     * @return Role
     */
    public function addRole(\SanSIS\Core\BaseBundle\Entity\Role $role = null)
    {
        if (!$this->role)
            $this->role = new ArrayCollection();

        $this->role->add($role);

        return $this;
    }
}
