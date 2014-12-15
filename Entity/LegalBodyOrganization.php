<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LegalBodyOrganization
 *
 * @ORM\Table(name="legal_body_organization", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"}), @ORM\UniqueConstraint(name="id_legal_body", columns={"id_legal_body"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class LegalBodyOrganization extends AbstractBase
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
     * @ORM\Column(name="cnpj", type="string", length=20, nullable=true)
     */
    private $cnpj;

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
     * @var \LegalBodyParent
     *
     * @ORM\ManyToOne(targetEntity="LegalBody")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_legal_body_parent", referencedColumnName="id")
     * })
     */
    private $idLegalBodyParent;

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
     * Set cnpj
     *
     * @param string $cnpj
     * @return LegalBodyOrganization
     */
    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;

        return $this;
    }

    /**
     * Get cnpj
     *
     * @return string 
     */
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * Set idLegalBody
     *
     * @param \SanSIS\Core\BaseBundle\Entity\LegalBody $idLegalBody
     * @return LegalBodyOrganization
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
     * Set idLegalBodyParent
     *
     * @param \SanSIS\Core\BaseBundle\Entity\LegalBody $idLegalBody
     * @return LegalBodyOrganization
     */
    public function setIdLegalBodyParent(\SanSIS\Core\BaseBundle\Entity\LegalBody $idLegalBodyParent = null)
    {
        $this->idLegalBodyParent = $idLegalBodyParent;

        return $this;
    }

    /**
     * Get idLegalBody
     *
     * @return \SanSIS\Core\BaseBundle\Entity\LegalBodyParent 
     */
    public function getIdLegalBodyParent()
    {
        return $this->idLegalBodyParent;
    }
}
