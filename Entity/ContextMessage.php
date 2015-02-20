<?php

namespace SanSIS\Core\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SanSIS\Core\BaseBundle\Entity\AbstractBase;

/**
 * ContextMessage
 *
 * @ORM\Table(name="core_context_message")
 * @ORM\Entity(repositoryClass="\SanSIS\Core\BaseBundle\EntityRepository\AbstractBase")
 */
class ContextMessage extends AbstractBase
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=256, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=false)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_start", type="datetime", nullable=false)
     */
    private $dateStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_end", type="datetime", nullable=true)
     */
    private $dateEnd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=false)
     */
    private $dateCreated;

    /**
     * @var integer
     *
     * @ORM\Column(name="objectId", type="integer", nullable=true)
     */
    private $objectId;

    /**
     * @var \SanSIS\Core\BaseBundle\Entity\Context
     *
     * @ORM\ManyToOne(targetEntity="Context")
     */
    private $context;

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
     * @return the string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     *
     * @return the string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     *
     * @return the DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     *
     * @param \DateTime $dateStart
     */
    public function setDateStart(\DateTime $dateStart)
    {
        $this->dateStart = $dateStart;
        return $this;
    }

    /**
     *
     * @return the DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     *
     * @param \DateTime $dateEnd
     */
    public function setDateEnd(\DateTime $dateEnd)
    {
        $this->dateEnd = $dateEnd;
        return $this;
    }

    /**
     *
     * @return the DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     *
     * @param \DateTime $dateCreated
     */
    public function setDateCreated(\DateTime $dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    /**
     *
     * @return the integer
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     *
     * @param $objectId
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
        return $this;
    }

    /**
     *
     * @return the Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     *
     * @param \SanSIS\Core\BaseBundle\Entity\Context $context
     */
    public function setContext(\SanSIS\Core\BaseBundle\Entity\Context $context)
    {
        $this->context = $context;
        return $this;
    }


}
