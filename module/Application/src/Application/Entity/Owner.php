<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Owner
 *
 * @ORM\Table(name="owner", indexes={@ORM\Index(name="fk_merchant_2", columns={"fk_merchant"}), @ORM\Index(name="fk_merchant_branch_34", columns={"fk_merchant_branch"})})
 * @ORM\MappedSuperclass
 */
class Owner
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="e_mail", type="string", length=100, nullable=false)
     */
    private $eMail = '';

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=40, nullable=false)
     */
    private $password = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="datetime", nullable=true)
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=1, nullable=true)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=25, nullable=true)
     */
    private $phoneNumber;

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
     * @return Owner
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
     * @return Owner
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
     * Set name
     *
     * @param string $name
     *
     * @return Owner
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
     * Set eMail
     *
     * @param string $eMail
     *
     * @return Owner
     */
    public function setEMail($eMail)
    {
        $this->eMail = $eMail;

        return $this;
    }

    /**
     * Get eMail
     *
     * @return string
     */
    public function getEMail()
    {
        return $this->eMail;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Owner
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Owner
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Owner
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return Owner
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set fkMerchant
     *
     * @param \Application\Entity\Merchant $fkMerchant
     *
     * @return Owner
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
     * @return Owner
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
