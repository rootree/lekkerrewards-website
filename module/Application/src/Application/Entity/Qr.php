<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Qr
 *
 * @ORM\Table(name="qr", indexes={@ORM\Index(name="customer", columns={"fk_customer"})})
 * @ORM\MappedSuperclass
 */
class Qr
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
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="code", type="integer", nullable=false)
     */
    private $code;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="source", type="integer", nullable=false)
     */
    private $source;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_used", type="datetime", nullable=true)
     */
    private $lastUsed;

    /**
     * @var \Application\Entity\Customer
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Customer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_customer", referencedColumnName="id")
     * })
     */
    private $fkCustomer;



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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Qr
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set code
     *
     * @param integer $code
     *
     * @return Qr
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Qr
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set source
     *
     * @param integer $source
     *
     * @return Qr
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return integer
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Qr
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set lastUsed
     *
     * @param \DateTime $lastUsed
     *
     * @return Qr
     */
    public function setLastUsed($lastUsed)
    {
        $this->lastUsed = $lastUsed;

        return $this;
    }

    /**
     * Get lastUsed
     *
     * @return \DateTime
     */
    public function getLastUsed()
    {
        return $this->lastUsed;
    }

    /**
     * Set fkCustomer
     *
     * @param \Application\Entity\Customer $fkCustomer
     *
     * @return Qr
     */
    public function setFkCustomer(\Application\Entity\Customer $fkCustomer = null)
    {
        $this->fkCustomer = $fkCustomer;

        return $this;
    }

    /**
     * Get fkCustomer
     *
     * @return \Application\Entity\Customer
     */
    public function getFkCustomer()
    {
        return $this->fkCustomer;
    }
}
