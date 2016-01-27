<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Redeem
 *
 * @ORM\Table(name="redeem", indexes={@ORM\Index(name="fk_merchant_7", columns={"fk_merchant"}), @ORM\Index(name="fk_merchant_branch_3", columns={"fk_merchant_branch"}), @ORM\Index(name="fk_customer_4", columns={"fk_customer"}), @ORM\Index(name="fk_history_rewardf", columns={"fk_history_reward"}), @ORM\Index(name="fk_rewarddr", columns={"fk_reward"})})
 * @ORM\MappedSuperclass
 */
class Redeem
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
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="spent", type="integer", nullable=false)
     */
    private $spent;

    /**
     * @var integer
     *
     * @ORM\Column(name="total", type="integer", nullable=false)
     */
    private $total;

    /**
     * @var \Application\Entity\Reward
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Reward")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_reward", referencedColumnName="id")
     * })
     */
    private $fkReward;

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
     * @var \Application\Entity\RewardHistory
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\RewardHistory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_history_reward", referencedColumnName="id")
     * })
     */
    private $fkHistoryReward;

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
     * @return Redeem
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
     * @return Redeem
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
     * @return Redeem
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
     * Set spent
     *
     * @param integer $spent
     *
     * @return Redeem
     */
    public function setSpent($spent)
    {
        $this->spent = $spent;

        return $this;
    }

    /**
     * Get spent
     *
     * @return integer
     */
    public function getSpent()
    {
        return $this->spent;
    }

    /**
     * Set total
     *
     * @param integer $total
     *
     * @return Redeem
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set fkReward
     *
     * @param \Application\Entity\Reward $fkReward
     *
     * @return Redeem
     */
    public function setFkReward(\Application\Entity\Reward $fkReward = null)
    {
        $this->fkReward = $fkReward;

        return $this;
    }

    /**
     * Get fkReward
     *
     * @return \Application\Entity\Reward
     */
    public function getFkReward()
    {
        return $this->fkReward;
    }

    /**
     * Set fkCustomer
     *
     * @param \Application\Entity\Customer $fkCustomer
     *
     * @return Redeem
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
     * Set fkHistoryReward
     *
     * @param \Application\Entity\RewardHistory $fkHistoryReward
     *
     * @return Redeem
     */
    public function setFkHistoryReward(\Application\Entity\RewardHistory $fkHistoryReward = null)
    {
        $this->fkHistoryReward = $fkHistoryReward;

        return $this;
    }

    /**
     * Get fkHistoryReward
     *
     * @return \Application\Entity\RewardHistory
     */
    public function getFkHistoryReward()
    {
        return $this->fkHistoryReward;
    }

    /**
     * Set fkMerchant
     *
     * @param \Application\Entity\Merchant $fkMerchant
     *
     * @return Redeem
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
     * @return Redeem
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
