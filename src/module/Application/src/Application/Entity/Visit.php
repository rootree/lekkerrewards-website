<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Visit
 *
 * @ORM\Table(name="visit", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="fk_merchant_3", columns={"fk_merchant"}), @ORM\Index(name="fk_customer_1", columns={"fk_customer"}), @ORM\Index(name="fk_merchant_branch", columns={"fk_merchant_branch"})})
 * @ORM\MappedSuperclass
 */
class Visit
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
    private $updatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="obtained_points", type="smallint", nullable=false)
     */
    private $obtainedPoints = '0';

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
     * @var \Application\Entity\Merchant
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Merchant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_merchant", referencedColumnName="id")
     * })
     */
    private $fkMerchant;

    /**
     * @var \Application\Entity\MerchantBranch
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\MerchantBranch")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_merchant_branch", referencedColumnName="id")
     * })
     */
    private $fkMerchantBranch;



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
     * @return Visit
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Visit
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
     * Set status
     *
     * @param integer $status
     *
     * @return Visit
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
     * Set obtainedPoints
     *
     * @param integer $obtainedPoints
     *
     * @return Visit
     */
    public function setObtainedPoints($obtainedPoints)
    {
        $this->obtainedPoints = $obtainedPoints;

        return $this;
    }

    /**
     * Get obtainedPoints
     *
     * @return integer
     */
    public function getObtainedPoints()
    {
        return $this->obtainedPoints;
    }

    /**
     * Set fkCustomer
     *
     * @param \Application\Entity\Customer $fkCustomer
     *
     * @return Visit
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

    /**
     * Set fkMerchant
     *
     * @param \Application\Entity\Merchant $fkMerchant
     *
     * @return Visit
     */
    public function setFkMerchant(\Application\Entity\Merchant $fkMerchant = null)
    {
        $this->fkMerchant = $fkMerchant;

        return $this;
    }

    /**
     * Get fkMerchant
     *
     * @return \Application\Entity\Merchant
     */
    public function getFkMerchant()
    {
        return $this->fkMerchant;
    }

    /**
     * Set fkMerchantBranch
     *
     * @param \Application\Entity\MerchantBranch $fkMerchantBranch
     *
     * @return Visit
     */
    public function setFkMerchantBranch(\Application\Entity\MerchantBranch $fkMerchantBranch = null)
    {
        $this->fkMerchantBranch = $fkMerchantBranch;

        return $this;
    }

    /**
     * Get fkMerchantBranch
     *
     * @return \Application\Entity\MerchantBranch
     */
    public function getFkMerchantBranch()
    {
        return $this->fkMerchantBranch;
    }
}
