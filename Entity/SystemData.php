<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SystemData
 *
 * @ORM\Table(name="core.system_data")
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
