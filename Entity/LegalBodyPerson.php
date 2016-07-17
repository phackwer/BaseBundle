<<<<<<< HEAD
<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection;

/**
 * LegalBodyPerson
 *
 * @ORM\Table(name="legal_body_person", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"}), @ORM\UniqueConstraint(name="id_legal_body", columns={"id_legal_body"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class LegalBodyPerson extends AbstractBase
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
     * @ORM\Column(name="cpf", type="string", length=15, nullable=true)
     */
    private $cpf;

    /**
     * @var \LegalBody
     *
     * @ORM\OneToOne(targetEntity="LegalBody")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_legal_body", referencedColumnName="id")
     * })
     */
    private $idLegalBody;
    
    /**
     * @var \SanSIS\Core\BaseBundle\Entity\User
     * @ORM\OneToOne(targetEntity="User", mappedBy="idLegalBodyPerson")
     */
    private $user;
    
    /**
     * @var \SanSIS\Core\BaseBundle\Entity\LegalBodyRelation
     * @ORM\OneToMany(targetEntity="LegalBodyRelation", mappedBy="idLegalBodyPerson")
     */
    private $professionalRelation;
    
    public function __construct(){
        $this->professionalRelation = new ArrayCollection();
    }

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
     * Set cpf
     *
     * @param string $cpf
     * @return LegalBodyPerson
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;

        return $this;
    }

    /**
     * Get cpf
     *
     * @return string 
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * Set idLegalBody
     *
     * @param \SanSIS\Core\BaseBundle\Entity\LegalBody $idLegalBody
     * @return LegalBodyPerson
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
     * Set user
     *
     * @param \SanSIS\Core\BaseBundle\Entity\User $user
     * @return LegalBody
     */
    public function setUser(\SanSIS\Core\BaseBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }
    
    /**
     * Get user
     *
     * @return \SanSIS\Core\BaseBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Set professionalRelation
     *
     * @param \SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection $professionalRelation
     * @innerEntity \SanSIS\Core\BaseBundle\Entity\LegalBodyRelation
     * @return LegalBodyPerson
     */
    public function setProfessionalRelation(\SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection $professionalRelation = null)
    {
        $this->professionalRelation = $professionalRelation;
    
        return $this;
    }
    
    /**
     * Get professionalRelation
     *
     * @return \SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection
     *
     */
    public function getProfessionalRelation()
    {
        return $this->professionalRelation;
    }
    
    /**
     * Add professionalRelation
     *
     * @param \SanSIS\Core\BaseBundle\Entity\LegalBodyRelation $professionalRelation
     * @return LegalBodyPerson
     */
    public function addProfessionalRelation(\SanSIS\Core\BaseBundle\Entity\LegalBodyRelation $professionalRelation = null)
    {
        $this->professionalRelation->add($professionalRelation);
    
        return $this;
    }
}
=======
<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection;

/**
 * LegalBodyPerson
 *
 * @ORM\Table(name="core_legal_body_person")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class LegalBodyPerson extends AbstractBase
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
     * @ORM\Column(name="cpf", type="string", length=15, nullable=true)
     */
    private $cpf;
    

    /**
     * @var string
     *
     * @ORM\Column(name="nr_matricula", type="string", length=50, nullable=true)
     */
    private $nrMatricula;

    /**
     * @var \LegalBody
     *
     * @ORM\OneToOne(targetEntity="LegalBody", inversedBy="person")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_legal_body", referencedColumnName="id")
     * })
     */
    private $idLegalBody;

    /**
     * @var \SanSIS\Core\BaseBundle\Entity\User
     * @ORM\OneToOne(targetEntity="User", mappedBy="idLegalBodyPerson")
     */
    private $user;

    /**
     * @var \SanSIS\Core\BaseBundle\Entity\LegalBodyRelation
     * @ORM\OneToMany(targetEntity="LegalBodyRelation", mappedBy="idLegalBodyPerson")
     */
    private $professionalRelation;

    public function __construct(){
        $this->professionalRelation = new ArrayCollection();
    }

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
     * Set cpf
     *
     * @param string $cpf
     * @return LegalBodyPerson
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;

        return $this;
    }

    /**
     * Get cpf
     *
     * @return string
     */
    public function getCpf()
    {
        return $this->cpf;
    }
    
    /**
     * Set nrMatricula
     *
     * @param string $nrMatricula
     * @return LegalBodyPerson
     */
    public function setNrMatricula($nrMatricula)
    {
        $this->nrMatricula = $nrMatricula;

        return $this;
    }

    /**
     * Get nrMatricula
     *
     * @return string
     */
    public function getNrMatricula()
    {
        return $this->nrMatricula;
    }

    /**
     * Set idLegalBody
     *
     * @param \SanSIS\Core\BaseBundle\Entity\LegalBody $idLegalBody
     * @return LegalBodyPerson
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
     * Set user
     *
     * @param \SanSIS\Core\BaseBundle\Entity\User $user
     * @return LegalBody
     */
    public function setUser(\SanSIS\Core\BaseBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \SanSIS\Core\BaseBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set professionalRelation
     *
     * @param \SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection $professionalRelation
     * @innerEntity \SanSIS\Core\BaseBundle\Entity\LegalBodyRelation
     * @return LegalBodyPerson
     */
    public function setProfessionalRelation(\SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection $professionalRelation = null)
    {
        $this->professionalRelation = $professionalRelation;

        return $this;
    }

    /**
     * Get professionalRelation
     *
     * @return \SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection
     *
     */
    public function getProfessionalRelation()
    {
        return $this->professionalRelation;
    }

    /**
     * Add professionalRelation
     *
     * @param \SanSIS\Core\BaseBundle\Entity\LegalBodyRelation $professionalRelation
     * @return LegalBodyPerson
     */
    public function addProfessionalRelation(\SanSIS\Core\BaseBundle\Entity\LegalBodyRelation $professionalRelation = null)
    {
        $this->professionalRelation->add($professionalRelation);

        return $this;
    }
}
>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
