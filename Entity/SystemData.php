<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SystemData
 *
 * @ORM\Table(name="system_data", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"}), @ORM\UniqueConstraint(name="id_organization", columns={"id_organization"}), @ORM\UniqueConstraint(name="ibram_identification", columns={"ibram_identification"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class SystemData extends AbstractBase
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
     * @ORM\Column(name="ibram_identification", type="string", length=256, nullable=true)
     */
    private $ibramIdentification;

    /**
     * @var string
     *
     * @ORM\Column(name="wallpaper", type="string", length=256, nullable=true)
     */
    private $wallpaper;

    /**
     * @var \LegalBodyOrganization
     *
     * @ORM\ManyToOne(targetEntity="LegalBodyOrganization")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_organization", referencedColumnName="id")
     * })
     */
    private $idOrganization;



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
     * Set ibramIdentification
     *
     * @param string $ibramIdentification
     * @return SystemData
     */
    public function setMarkMonitorIdentification($ibramIdentification)
    {
        $this->ibramIdentification = $ibramIdentification;

        return $this;
    }

    /**
     * Get ibramIdentification
     *
     * @return string 
     */
    public function getMarkMonitorIdentification()
    {
        return $this->ibramIdentification;
    }

    /**
     * Set wallpaper
     *
     * @param string $wallpaper
     * @return SystemData
     */
    public function setWallpaper($wallpaper)
    {
        $this->wallpaper = $wallpaper;

        return $this;
    }

    /**
     * Get wallpaper
     *
     * @return string 
     */
    public function getWallpaper()
    {
        return $this->wallpaper;
    }

    /**
     * Set idOrganization
     *
     * @param \SanSIS\Core\BaseBundle\Entity\LegalBodyOrganization $idOrganization
     * @return SystemData
     */
    public function setIdOrganization(\SanSIS\Core\BaseBundle\Entity\LegalBodyOrganization $idOrganization = null)
    {
        $this->idOrganization = $idOrganization;

        return $this;
    }

    /**
     * Get idOrganization
     *
     * @return \SanSIS\Core\BaseBundle\Entity\LegalBodyOrganization 
     */
    public function getIdOrganization()
    {
        return $this->idOrganization;
    }
}