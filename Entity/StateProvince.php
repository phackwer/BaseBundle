<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StateProvince
 *
 * @ORM\Table(name="core.state_province")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class StateProvince extends AbstractBase
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
     * @var string
     *
     * @ORM\Column(name="acronym", type="string", length=2, nullable=true)
     */
    private $acronym;

    /**
     * @var integer
     *
     * @ORM\Column(name="status_tuple", type="integer", nullable=false)
     */
    private $statusTuple = '1';

    /**
     * @var \Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_country", referencedColumnName="id")
     * })
     */
    private $idCountry;



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
     * @return StateProvince
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
     * Set acronym
     *
     * @param string $acronym
     * @return StateProvince
     */
    public function setAcronym($acronym)
    {
        $this->acronym = $acronym;

        return $this;
    }

    /**
     * Get acronym
     *
     * @return string
     */
    public function getAcronym()
    {
        return $this->acronym;
    }

    /**
     * Set statusTuple
     *
     * @param integer $statusTuple
     * @return StateProvince
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
     * Set idCountry
     *
     * @param \SanSIS\Core\BaseBundle\Entity\Country $idCountry
     * @return StateProvince
     */
    public function setIdCountry(\SanSIS\Core\BaseBundle\Entity\Country $idCountry = null)
    {
        $this->idCountry = $idCountry;

        return $this;
    }

    /**
     * Get idCountry
     *
     * @return \SanSIS\Core\BaseBundle\Entity\Country
     */
    public function getIdCountry()
    {
        return $this->idCountry;
    }
}
