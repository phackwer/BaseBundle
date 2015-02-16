<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 *
 * @ORM\Table(name="core.role")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class Role extends AbstractBase
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
     * @ORM\Column(name="term", type="string", length=256, nullable=false)
     */
    private $term;

    /**
     * @var integer
     *
     * @ORM\Column(name="human", type="integer", nullable=false)
     */
    private $human = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="status_tuple", type="integer", nullable=false)
     */
    private $statusTuple = '1';

    /**
     * @ORM\ManyToMany(targetEntity="Actor", mappedBy="role")
     *
     */
    private $actor;

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
     * Set term
     *
     * @param string $term
     * @return Role
     */
    public function setTerm($term)
    {
        $this->term = $term;

        return $this;
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
     * Set human
     *
     * @param integer $human
     * @return Role
     */
    public function setHuman($human)
    {
        $this->human = $human;

        return $this;
    }

    /**
     * Get human
     *
     * @return integer
     */
    public function getHuman()
    {
        return $this->human;
    }

    /**
     * Set statusTuple
     *
     * @param integer $statusTuple
     * @return Role
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

    public function removeActor(\SanSIS\Core\BaseBundle\Entity\Actor $actor)
    {
        if (!$this->actor->contains($actor)) {
            return;
        }
        $this->actor->removeElement($actor);
        $actor->removeRole($this);
    }

    /**
     * Set actor
     *
     * @param \SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection $actor
     * @innerEntity \SanSIS\Core\BaseBundle\Entity\Actor
     * @return Actor
     */
    public function setActor(\SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection $actor = null)
    {
        $this->actor = $actor;

        return $this;
    }

    /**
     * Get actor
     *
     * @return \SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection
     */
    public function getActor()
    {
        return $this->actor;
    }

    /**
     * Add actor
     *
     * @param \SanSIS\Core\BaseBundle\Entity\Actor $actor
     * @return Actor
     */
    public function addActor(\SanSIS\Core\BaseBundle\Entity\Actor $actor = null)
    {
        if (!$this->actor) {
            $this->actor = new ArrayCollection();
        }

        $this->actor->add($actor);

        return $this;
    }
}
