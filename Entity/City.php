<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * City
 *
 * @ORM\Table(name="core_city")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class City extends AbstractBase
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
     * @ORM\Column(name="status_tuple", type="integer", nullable=false, options={"default" = 1})
     */
    private $statusTuple = 1;

    /**
     * @var \StateProvince
     *
     * @ORM\ManyToOne(targetEntity="StateProvince")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_state_province", referencedColumnName="id")
     * })
     */
    private $idStateProvince;



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
     * @return City
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
     * Set statusTuple
     *
     * @param integer $statusTuple
     * @return City
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

    /**
     * Set idStateProvince
     *
     * @param \SanSIS\Core\BaseBundle\Entity\StateProvince $idStateProvince
     * @return City
     */
    public function setIdStateProvince(\SanSIS\Core\BaseBundle\Entity\StateProvince $idStateProvince = null)
    {
        $this->idStateProvince = $idStateProvince;

        return $this;
    }

    /**
     * Get idStateProvince
     *
     * @return \SanSIS\Core\BaseBundle\Entity\StateProvince
     */
    public function getIdStateProvince()
    {
        return $this->idStateProvince;
    }
}
