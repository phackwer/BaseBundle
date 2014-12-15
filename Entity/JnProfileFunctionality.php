<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JnProfileFunctionality
 *
 * @ORM\Table(name="jn_profile_functionality", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="id_functionality", columns={"id_functionality"}), @ORM\Index(name="id_profile", columns={"id_profile"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class JnProfileFunctionality extends AbstractBase
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
     * @var \Profile
     *
     * @ORM\ManyToOne(targetEntity="Profile")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_profile", referencedColumnName="id")
     * })
     */
    private $idProfile;

    /**
     * @var \Functionality
     *
     * @ORM\ManyToOne(targetEntity="Functionality")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_functionality", referencedColumnName="id")
     * })
     */
    private $idFunctionality;

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
     * Set idProfile
     *
     * @param \SanSIS\Core\BaseBundle\Entity\Profile $idProfile
     * @return JnProfileFunctionality
     */
    public function setIdProfile(\SanSIS\Core\BaseBundle\Entity\Profile $idProfile = null)
    {
        $this->idProfile = $idProfile;

        return $this;
    }

    /**
     * Get idProfile
     *
     * @return \SanSIS\Core\BaseBundle\Entity\Profile
     */
    public function getIdProfile()
    {
        return $this->idProfile;
    }

    /**
     * Set idFunctionality
     *
     * @param \SanSIS\Core\BaseBundle\Entity\Functionality $idFunctionality
     * @return JnProfileFunctionality
     */
    public function setIdFunctionality(\SanSIS\Core\BaseBundle\Entity\Functionality $idFunctionality = null)
    {
        $this->idFunctionality = $idFunctionality;

        return $this;
    }

    /**
     * Get idFunctionality
     *
     * @return \SanSIS\Core\BaseBundle\Entity\Functionality 
     */
    public function getIdFunctionality()
    {
        return $this->idFunctionality;
    }
}
