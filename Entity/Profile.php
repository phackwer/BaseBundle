<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Profile
 *
 * @ORM\Table(name="profile", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class Profile extends AbstractBase
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
     * @var integer
     *
     * @ORM\Column(name="status_tuple", type="integer", nullable=false)
     */
    private $statusTuple = '1';

    /**
     * @ORM\ManyToMany(targetEntity="Functionality")
     * @ORM\JoinTable(name="jn_profile_functionality",
     *      joinColumns={@ORM\JoinColumn(name="id_profile", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_functionality", referencedColumnName="id")}
     *      )
     */
    private $functionalities;

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
     * Get term
     *
     * @return string 
     */
    public function getFunctionalities()
    {
        return $this->functionalities;
    }

    /**
     * Set statusTuple
     *
     * @param integer $statusTuple
     * @return Country
     */
    public function setStatusTuple($statusTuple)
    {
        $this->statusTuple = $statusTuple;

        return $this;
    }

    /**
     * Get statusTuple
     *
     * @return integer 
     */
    public function getStatusTuple()
    {
        return $this->statusTuple;
    }
}
