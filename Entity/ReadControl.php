<?php

namespace \SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SanSIS\Core\BaseBundle\Entity\AbstractBase;

/**
 * ReadControl
 *
 * @ORM\Table(name="core_read_control")
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class ReadControl extends AbstractBase
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_read", type="datetime", nullable=false)
     */
    private $dateRead;

    /**
     * @var \SanSIS\Core\BaseBundle\Entity\ContextMessage
     *
     * @ORM\ManyToOne(targetEntity="\SanSIS\Core\BaseBundle\Entity\ContextMessage")
     */
    private $contextMessage;

    /**
     * @var \SanSIS\Core\BaseBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\SanSIS\Core\BaseBundle\Entity\User")
     */
    private $user;

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
     *
     * @return the DateTime
     */
    public function getDateRead()
    {
        return $this->dateRead;
    }

    /**
     *
     * @param \DateTime $dateRead
     */
    public function setDateRead(\DateTime $dateRead)
    {
        $this->dateRead = $dateRead;
        return $this;
    }

    /**
     *
     * @return the ContextMessage
     */
    public function getContextMessage()
    {
        return $this->contextMessage;
    }

    /**
     *
     * @param $contextMessage
     */
    public function setContextMessage(\SanSIS\Core\BaseBundle\Entity\ContextMessage $contextMessage)
    {
        $this->contextMessage = $contextMessage;
        return $this;
    }

    /**
     *
     * @return the User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     * @param $user
     */
    public function setUser(\SanSIS\Core\BaseBundle\Entity\User $user)
    {
        $this->user = $user;
        return $this;
    }


}
