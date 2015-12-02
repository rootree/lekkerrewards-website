<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MerchantsCustomers
 *
 * @ORM\Table(name="merchants__customers", indexes={@ORM\Index(name="fk_merchant_8", columns={"fk_merchant"}), @ORM\Index(name="fk_customer_3", columns={"fk_customer"}), @ORM\Index(name="fk_merchant_branch_8", columns={"fk_merchant_branch"})})
 * @ORM\MappedSuperclass
 */
class MerchantsCustomers
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="first_at", type="datetime", nullable=false)
     */
    private $firstAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="visits", type="smallint", nullable=false)
     */
    private $visits = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="redeems", type="smallint", nullable=false)
     */
    private $redeems = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="smallint", nullable=false)
     */
    private $points = '0';

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
     * @return MerchantsCustomers
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
     * @return MerchantsCustomers
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
     * Set firstAt
     *
     * @param \DateTime $firstAt
     *
     * @return MerchantsCustomers
     */
    public function setFirstAt($firstAt)
    {
        $this->firstAt = $firstAt;

        return $this;
    }

    /**
     * Get firstAt
     *
     * @return \DateTime
     */
    public function getFirstAt()
    {
        return $this->firstAt;
    }

    /**
     * Set visits
     *
     * @param integer $visits
     *
     * @return MerchantsCustomers
     */
    public function setVisits($visits)
    {
        $this->visits = $visits;

        return $this;
    }

    /**
     * Get visits
     *
     * @return integer
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * Set redeems
     *
     * @param integer $redeems
     *
     * @return MerchantsCustomers
     */
    public function setRedeems($redeems)
    {
        $this->redeems = $redeems;

        return $this;
    }

    /**
     * Get redeems
     *
     * @return integer
     */
    public function getRedeems()
    {
        return $this->redeems;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return MerchantsCustomers
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set fkCustomer
     *
     * @param \Application\Entity\Customer $fkCustomer
     *
     * @return MerchantsCustomers
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
     * @return MerchantsCustomers
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
     * @return MerchantsCustomers
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
