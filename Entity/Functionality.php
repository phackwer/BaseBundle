<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Functionality
 *
 * @ORM\Table(name="functionality", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class Functionality extends AbstractBase
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
     * @ORM\Column(name="term", type="string", length=128, nullable=false)
     */
    private $term;

    /**
     * @var string
     *
     * @ORM\Column(name="role_word", type="string", length=128, nullable=false)
     */
    private $roleWord;

    /**
     * @ORM\ManyToMany(targetEntity="Profile")
     * @ORM\JoinTable(name="jn_profile_functionality",
     *      joinColumns={@ORM\JoinColumn(name="id_functionality", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_profile", referencedColumnName="id")}
     *      )
     */
    private $profiles;

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
     * Get term
     *
     * @return string 
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Get roleWord
     *
     * @return string 
     */
    public function getRoleWord()
    {
        return $this->roleWord;
    }
}
