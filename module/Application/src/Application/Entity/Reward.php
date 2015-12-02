<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reward
 *
 * @ORM\Table(name="reward", indexes={@ORM\Index(name="fk_merchant_4", columns={"fk_merchant"}), @ORM\Index(name="fk_owner_1", columns={"fk_owner"}), @ORM\Index(name="fk_merchant_branch_2", columns={"fk_merchant_branch"})})
 * @ORM\MappedSuperclass
 */
class Reward
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
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=false)
     */
    private $parentId = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="smallint", nullable=false)
     */
    private $points;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=500, nullable=false)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=32, nullable=false)
     */
    private $code = '';

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
     * @var \Application\Entity\Owner
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Owner")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_owner", referencedColumnName="id")
     * })
     */
    private $fkOwner;



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
     * @return Reward
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
     * @return Reward
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
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Reward
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set parentId
     *
     * @param integer $parentId
     *
     * @return Reward
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return Reward
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
     * Set name
     *
     * @param string $name
     *
     * @return Reward
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
     * Set code
     *
     * @param string $code
     *
     * @return Reward
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set fkMerchant
     *
     * @param \Application\Entity\Merchant $fkMerchant
     *
     * @return Reward
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
     * @return Reward
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

    /**
     * Set fkOwner
     *
     * @param \Application\Entity\Owner $fkOwner
     *
     * @return Reward
     */
    public function setFkOwner(\Application\Entity\Owner $fkOwner = null)
    {
        $this->fkOwner = $fkOwner;

        return $this;
    }

    /**
     * Get fkOwner
     *
     * @return \Application\Entity\Owner
     */
    public function getFkOwner()
    {
        return $this->fkOwner;
    }
}
