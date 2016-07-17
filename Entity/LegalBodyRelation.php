<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection;

/**
 * LegalBodyRelation
 *
 * @ORM\Table(name="legal_body_relation", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"}), @ORM\UniqueConstraint(name="id_job_position", columns={"id_job_position"})}, indexes={@ORM\Index(name="id_legal_body_organization", columns={"id_legal_body_organization"}), @ORM\Index(name="id_legal_body_person", columns={"id_legal_body_person"}), @ORM\Index(name="id_legal_body_relation_type", columns={"id_legal_body_relation_type"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class LegalBodyRelation extends AbstractBase
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
     * @var \DateTime
     *
     * @ORM\Column(name="earliest_date", type="date", nullable=true)
     */
    private $earliestDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="latest_date", type="date", nullable=true)
     */
    private $latestDate;

    /**
     * @var string
     *
     * @ORM\Column(name="job_position", type="string", length=512, nullable=false)
     */
    private $jobPosition;

    /**
     * @var \LegalBodyRelationType
     *
     * @ORM\ManyToOne(targetEntity="LegalBodyRelationType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_legal_body_relation_type", referencedColumnName="id")
     * })
     */
    private $idLegalBodyRelationType;

    /**
     * @var \LegalBodyOrganization
     *
     * @ORM\ManyToOne(targetEntity="LegalBodyOrganization")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_legal_body_organization", referencedColumnName="id")
     * })
     */
    private $idLegalBodyOrganization;

    /**
     * @var \LegalBodyPerson
     *
     * @ORM\ManyToOne(targetEntity="LegalBodyPerson")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_legal_body_person", referencedColumnName="id")
     * })
     */
    private $idLegalBodyPerson;
    
    /**
     * @ORM\ManyToMany(targetEntity="Profile")
     * @ORM\JoinTable(name="jn_legal_body_relation_profile",
     *      joinColumns={@ORM\JoinColumn(name="id_legal_body_relation", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_profile", referencedColumnName="id")}
     *      )
     */
    private $profile;
    

    public function __construct(){
        $this->profile = new ArrayCollection();
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
     * Set jobPosition
     *
     * @param string $jobPosition
     * @return LegalBodyRelation
     */
    public function setJobPosition($jobPosition)
    {
        $this->jobPosition = $jobPosition;
    
        return $this;
    }
    
    /**
     * Get jobPosition
     *
     * @return string
     */
    public function getJobPosition()
    {
        return $this->jobPosition;
    }

    /**
     * Set earliestDate
     *
     * @param \DateTime $earliestDate
     * @return LegalBodyRelation
     */
    public function setEarliestDate($earliestDate)
    {
        $this->earliestDate = $earliestDate;

        return $this;
    }

    /**
     * Get earliestDate
     *
     * @return \DateTime 
     */
    public function getEarliestDate()
    {
        return $this->earliestDate;
    }

    /**
     * Set latestDate
     *
     * @param \DateTime $latestDate
     * @return LegalBodyRelation
     */
    public function setLatestDate($latestDate)
    {
        $this->latestDate = $latestDate;

        return $this;
    }

    /**
     * Get latestDate
     *
     * @return \DateTime 
     */
    public function getLatestDate()
    {
        return $this->latestDate;
    }

    /**
     * Set idLegalBodyRelationType
     *
     * @param \SanSIS\Core\BaseBundle\Entity\LegalBodyRelationType $idLegalBodyRelationType
     * @return LegalBodyRelation
     */
    public function setIdLegalBodyRelationType(\SanSIS\Core\BaseBundle\Entity\LegalBodyRelationType $idLegalBodyRelationType = null)
    {
        $this->idLegalBodyRelationType = $idLegalBodyRelationType;

        return $this;
    }

    /**
     * Get idLegalBodyRelationType
     *
     * @return \SanSIS\Core\BaseBundle\Entity\LegalBodyRelationType 
     */
    public function getIdLegalBodyRelationType()
    {
        return $this->idLegalBodyRelationType;
    }

    /**
     * Set idLegalBodyOrganization
     *
     * @param \SanSIS\Core\BaseBundle\Entity\LegalBodyOrganization $idLegalBodyOrganization
     * @return LegalBodyRelation
     */
    public function setIdLegalBodyOrganization(\SanSIS\Core\BaseBundle\Entity\LegalBodyOrganization $idLegalBodyOrganization = null)
    {
        $this->idLegalBodyOrganization = $idLegalBodyOrganization;

        return $this;
    }

    /**
     * Get idLegalBodyOrganization
     *
     * @return \SanSIS\Core\BaseBundle\Entity\LegalBodyOrganization 
     */
    public function getIdLegalBodyOrganization()
    {
        return $this->idLegalBodyOrganization;
    }

    /**
     * Set idLegalBodyPerson
     *
     * @param \SanSIS\Core\BaseBundle\Entity\LegalBodyPerson $idLegalBodyPerson
     * @return LegalBodyRelation
     */
    public function setIdLegalBodyPerson(\SanSIS\Core\BaseBundle\Entity\LegalBodyPerson $idLegalBodyPerson = null)
    {
        $this->idLegalBodyPerson = $idLegalBodyPerson;

        return $this;
    }

    /**
     * Get idLegalBodyPerson
     *
     * @return \SanSIS\Core\BaseBundle\Entity\LegalBodyPerson 
     */
    public function getIdLegalBodyPerson()
    {
        return $this->idLegalBodyPerson;
    }
    
    /**
     * Set profile
     *
     * @param \SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection $profile
     * @innerEntity \SanSIS\Core\BaseBundle\Entity\Profile
     * @return Profile
     */
    public function setProfile(\SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection $profile = null)
    {
        $this->profile = $profile;
    
        return $this;
    }
    
    /**
     * Get profile
     *
     * @return \SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection
     */
    public function getProfile()
    {
        return $this->profile;
    }
    
    /**
     * Add profile
     *
     * @param \SanSIS\Core\BaseBundle\Entity\Profile $profile
     * @return Profile
     */
    public function addProfile(\SanSIS\Core\BaseBundle\Entity\Profile $profile = null)
    {
        $this->profile->add($profile);
    
        return $this;
    }
}
