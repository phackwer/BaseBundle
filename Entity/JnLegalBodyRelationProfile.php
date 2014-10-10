<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JnLegalBodyRelationProfile
 *
 * @ORM\Table(name="jn_legal_body_relation_profile", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="id_legal_body_relation", columns={"id_legal_body_relation"}), @ORM\Index(name="id_profile", columns={"id_profile"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class JnLegalBodyRelationProfile extends AbstractBase
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
     * @var \LegalBodyRelation
     *
     * @ORM\ManyToOne(targetEntity="LegalBodyRelation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_legal_body_relation", referencedColumnName="id")
     * })
     */
    private $idLegalBodyRelation;

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
     * @return JnLegalBodyRelationProfile
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
     * Set idLegalBodyRelation
     *
     * @param \SanSIS\Core\BaseBundle\Entity\LegalBodyRelation $idLegalBodyRelation
     * @return JnLegalBodyRelationProfile
     */
    public function setIdLegalBodyRelation(\SanSIS\Core\BaseBundle\Entity\LegalBodyRelation $idLegalBodyRelation = null)
    {
        $this->idLegalBodyRelation = $idLegalBodyRelation;

        return $this;
    }

    /**
     * Get idLegalBodyRelation
     *
     * @return \SanSIS\Core\BaseBundle\Entity\LegalBodyRelation 
     */
    public function getIdLegalBodyRelation()
    {
        return $this->idLegalBodyRelation;
    }
}
