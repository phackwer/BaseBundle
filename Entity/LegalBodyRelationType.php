<<<<<<< HEAD
<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LegalBodyRelationType
 *
 * @ORM\Table(name="legal_body_relation_type", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class LegalBodyRelationType extends AbstractBase
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
     * @var integer
     *
     * @ORM\Column(name="status_tuple", type="integer", nullable=false)
     */
    private $statusTuple = '1';



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
     * @return LegalBodyRelationType
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
     * Set statusTuple
     *
     * @param integer $statusTuple
     * @return LegalBodyRelationType
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

/**
 * LegalBodyRelationType
 *
 * @ORM\Table(name="core_legal_body_relation_type")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class LegalBodyRelationType extends AbstractBase
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
     * @var integer
     *
     * @ORM\Column(name="status_tuple", type="integer", nullable=false)
     */
    private $statusTuple = '1';



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
     * @return LegalBodyRelationType
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
     * Set statusTuple
     *
     * @param integer $statusTuple
     * @return LegalBodyRelationType
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
>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
