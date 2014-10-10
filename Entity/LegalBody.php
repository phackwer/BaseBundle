<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LegalBody
 *
 * @ORM\Table(name="legal_body", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="id_city", columns={"id_city"}), @ORM\Index(name="id_country", columns={"id_country"}), @ORM\Index(name="id_legal_body_type", columns={"id_legal_body_type"}), @ORM\Index(name="id_state_province", columns={"id_state_province"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class LegalBody extends AbstractBase
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
     * @ORM\Column(name="name", type="string", length=512, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=512, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=256, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=512, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=521, nullable=true)
     */
    private $url;

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
     * @ORM\Column(name="city", type="string", length=512, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state_province", type="string", length=512, nullable=true)
     */
    private $stateProvince;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=512, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="zipcode", type="string", length=50, nullable=true)
     */
    private $zipcode;

    /**
     * @var integer
     *
     * @ORM\Column(name="status_tuple", type="integer", nullable=false)
     */
    private $statusTuple = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="place_of_birth", type="string", length=512, nullable=true)
     */
    private $placeOfBirth;

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
     * @var \City
     *
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_city", referencedColumnName="id")
     * })
     */
    private $idCity;

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
     * @var \LegalBodyType
     *
     * @ORM\ManyToOne(targetEntity="LegalBodyType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_legal_body_type", referencedColumnName="id")
     * })
     */
    private $idLegalBodyType;

    /**
     * @var \SanSIS\Core\BaseBundle\Entity\LegalBodyPerson
     * @ORM\OneToOne(targetEntity="LegalBodyPerson", mappedBy="idLegalBody")
     */
    private $person;
    
    /**
     * @var \SanSIS\Core\BaseBundle\Entity\LegalBodyOrganization
     * @ORM\OneToOne(targetEntity="LegalBodyOrganization", mappedBy="idLegalBody")
     */
    private $organization;
    
    /**
     * @var string
     *
     * @ORM\Column(name="annotation", type="text", nullable=false)
     */
    private $annotation;

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
     * Set name
     *
     * @param string $name
     * @return LegalBody
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return LegalBody
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return LegalBody
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return LegalBody
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return LegalBody
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set earliestDate
     *
     * @param \DateTime $earliestDate
     * @return LegalBody
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
     * @return LegalBody
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
     * Set city
     *
     * @param string $city
     * @return LegalBody
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set stateProvince
     *
     * @param string $stateProvince
     * @return LegalBody
     */
    public function setStateProvince($stateProvince)
    {
        $this->stateProvince = $stateProvince;

        return $this;
    }

    /**
     * Get stateProvince
     *
     * @return string 
     */
    public function getStateProvince()
    {
        return $this->stateProvince;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return LegalBody
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     * @return LegalBody
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string 
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set statusTuple
     *
     * @param integer $statusTuple
     * @return LegalBody
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
     * Set placeOfBirth
     *
     * @param string $placeOfBirth
     * @return LegalBody
     */
    public function setPlaceOfBirth($placeOfBirth)
    {
        $this->placeOfBirth = $placeOfBirth;

        return $this;
    }

    /**
     * Get placeOfBirth
     *
     * @return string 
     */
    public function getPlaceOfBirth()
    {
        return $this->placeOfBirth;
    }
    
    /**
     * Set annotation
     *
     * @param string $annotation
     * @return ActorAnnotation
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;

        return $this;
    }

    /**
     * Get annotation
     *
     * @return string 
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * Set idStateProvince
     *
     * @param \SanSIS\Core\BaseBundle\Entity\StateProvince $idStateProvince
     * @return LegalBody
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

    /**
     * Set idCity
     *
     * @param \SanSIS\Core\BaseBundle\Entity\City $idCity
     * @return LegalBody
     */
    public function setIdCity(\SanSIS\Core\BaseBundle\Entity\City $idCity = null)
    {
        $this->idCity = $idCity;

        return $this;
    }

    /**
     * Get idCity
     *
     * @return \SanSIS\Core\BaseBundle\Entity\City 
     */
    public function getIdCity()
    {
        return $this->idCity;
    }

    /**
     * Set idCountry
     *
     * @param \SanSIS\Core\BaseBundle\Entity\Country $idCountry
     * @return LegalBody
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

    /**
     * Set idLegalBodyType
     *
     * @param \SanSIS\Core\BaseBundle\Entity\LegalBodyType $idLegalBodyType
     * @return LegalBody
     */
    public function setIdLegalBodyType(\SanSIS\Core\BaseBundle\Entity\LegalBodyType $idLegalBodyType = null)
    {
        $this->idLegalBodyType = $idLegalBodyType;

        return $this;
    }

    /**
     * Get idLegalBodyType
     *
     * @return \SanSIS\Core\BaseBundle\Entity\LegalBodyType 
     */
    public function getIdLegalBodyType()
    {
        return $this->idLegalBodyType;
    }
    
    /**
     * Set person
     *
     * @param \SanSIS\Core\BaseBundle\Entity\LegalBodyPerson $person
     * @return LegalBody
     */
    public function setPerson(\SanSIS\Core\BaseBundle\Entity\LegalBodyPerson $person = null)
    {
        $this->person = $person;
    
        return $this;
    }
    
    /**
     * Get person
     *
     * @return \SanSIS\Core\BaseBundle\Entity\LegalBodyPerson
     */
    public function getPerson()
    {
        return $this->person;
    }
    
    /**
     * Set organization
     *
     * @param \SanSIS\Core\BaseBundle\Entity\LegalBodyOrganization $organization
     * @return LegalBody
     */
    public function setOrganization(\SanSIS\Core\BaseBundle\Entity\LegalBodyOrganization $organization = null)
    {
        $this->organization = $organization;
    
        return $this;
    }
    
    /**
     * Get organization
     *
     * @return \SanSIS\Core\BaseBundle\Entity\LegalBodyOrganization
     */
    public function getOrganization()
    {
        return $this->organization;
    }
}
