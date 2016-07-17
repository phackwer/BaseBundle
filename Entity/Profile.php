<<<<<<< HEAD
<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Profile
 *
 * @ORM\Table(name="profile", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class Profile extends AbstractBase
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
     * @ORM\Column(name="term", type="string", length=128, nullable=false)
     */
    private $term;

    /**
     * @var integer
     *
     * @ORM\Column(name="status_tuple", type="integer", nullable=false)
     */
    private $statusTuple = '1';

    /**
     * @ORM\ManyToMany(targetEntity="Functionality")
     * @ORM\JoinTable(name="jn_profile_functionality",
     *      joinColumns={@ORM\JoinColumn(name="id_profile", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_functionality", referencedColumnName="id")}
     *      )
     */
    private $functionalities;

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
     * Get term
     *
     * @return string 
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Get term
     *
     * @return string 
     */
    public function getFunctionalities()
    {
        return $this->functionalities;
    }

    /**
     * Set statusTuple
     *
     * @param integer $statusTuple
     * @return Country
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
}
=======
<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection;

/**
 * Profile
 *
 * @ORM\Table(name="core_profile")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class Profile extends AbstractBase
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
     * @ORM\Column(name="term", type="string", length=128, nullable=false)
     */
    private $term;

    /**
     * @var integer
     *
     * @ORM\Column(name="status_tuple", type="integer", nullable=false)
     */
    private $statusTuple = '1';

    /**
     * @ORM\ManyToMany(targetEntity="Functionality", inversedBy="profiles")
     * @ORM\JoinTable(name="core_jn_profile_functionality",
     *      joinColumns={@ORM\JoinColumn(name="id_profile", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_functionality", referencedColumnName="id")}
     *      )
     */
    private $functionalities;

    /**
     * @ORM\ManyToMany(targetEntity="LegalBodyRelation", mappedBy="profile")
     *
     */
    private $legalbodyrelations;

    /**
     * Define as collections
     */
    public function __construct()
    {
        $this->functionalities = new ArrayCollection();
    }

    /**
     * @return \SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection
     */
    public function getFunctionalities()
    {
        return $this->functionalities;
    }

    /**
     * @param \SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection $functionalities
     * @innerEntity \SanSIS\Core\BaseBundle\Entity\Functionality
     * @return LegalBodyPerson
     */
    public function setFunctionalities(\SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection $functionalities = null)
    {
        $this->functionalities = $functionalities;

        return $this;
    }

    /**
     * @param \SanSIS\Core\BaseBundle\Entity\Functionality $professionalRelation
     * @return Profile
     */
    public function addFunctionalities(\SanSIS\Core\BaseBundle\Entity\Functionality $functionalities = null)
    {
        if (!$this->functionalities) {
            $this->functionalities = new ArrayCollection();
        }

        $this->functionalities->add($functionalities);

        return $this;
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
     * Get term
     *
     * @return string
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Get term
     *
     * @return string
     */
    public function setTerm($term)
    {
        $this->term = $term;
        return $this;
    }

    /**
     * Set statusTuple
     *
     * @param integer $statusTuple
     * @return Country
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

    public function removeLegalBodyRelation(\SanSIS\Core\BaseBundle\Entity\LegalBodyRelation $legalbodyrelation)
    {
        if (!$this->legalbodyrelations->contains($legalbodyrelation)) {
            return;
        }
        $this->legalbodyrelations->removeElement($legalbodyrelation);
        $legalbodyrelation->removeProfile($this);
    }

    public function removeFunctionality(\SanSIS\Core\BaseBundle\Entity\Functionality $functionality)
    {
        if (!$this->functionalities->contains($functionality)) {
            return;
        }
        $this->functionalities->removeElement($functionality);
        $functionality->removeProfile($this);
    }
}
>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
