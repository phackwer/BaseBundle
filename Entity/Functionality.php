<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Functionality
 *
 * @ORM\Table(name="core_functionality")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class Functionality extends AbstractBase
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
     * @var string
     *
     * @ORM\Column(name="role_word", type="string", length=128, nullable=false)
     */
    private $roleWord;

    /**
     * @ORM\ManyToMany(targetEntity="Profile", mappedBy="functionalities")
     *
     */
    private $profiles;

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
     * Get roleWord
     *
     * @return string
     */
    public function getRoleWord()
    {
        return $this->roleWord;
    }

    public function removeProfile(\SanSIS\Core\BaseBundle\Entity\Profile $profile)
    {
        if (!$this->profiles->contains($profile)) {
            return;
        }
        $this->profiles->removeElement($profile);
        $profile->removeFunctionality($this);
    }
}
