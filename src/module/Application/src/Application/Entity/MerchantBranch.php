<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MerchantBranch
 *
 * @ORM\Table(name="merchant_branch", indexes={@ORM\Index(name="fk_merchant_1", columns={"fk_merchant"}), @ORM\Index(name="fk_city_1", columns={"fk_city"}), @ORM\Index(name="fk_country_1", columns={"fk_country"}), @ORM\Index(name="fk_state", columns={"fk_state"})})
 * @ORM\MappedSuperclass
 */
class MerchantBranch
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
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=8, nullable=false)
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=8, nullable=false)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=25, nullable=false)
     */
    private $phoneNumber = '';

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=100, nullable=false)
     */
    private $address = '';

    /**
     * @var string
     *
     * @ORM\Column(name="zipcode", type="string", length=10, nullable=false)
     */
    private $zipcode = '';

    /**
     * @var string
     *
     * @ORM\Column(name="permalink_path", type="string", length=100, nullable=false)
     */
    private $permalinkPath = '';

    /**
     * @var string
     *
     * @ORM\Column(name="time_offset", type="string", length=10, nullable=false)
     */
    private $timeOffset = '+02:00';

    /**
     * @var string
     *
     * @ORM\Column(name="api_key", type="string", length=40, nullable=false)
     */
    private $apiKey = '';

    /**
     * @var string
     *
     * @ORM\Column(name="e_mail", type="string", length=100, nullable=true)
     */
    private $eMail;

    /**
     * @var \Application\Entity\City
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\City")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_city", referencedColumnName="id")
     * })
     */
    private $fkCity;

    /**
     * @var \Application\Entity\Country
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_country", referencedColumnName="id")
     * })
     */
    private $fkCountry;

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
     * @var \Application\Entity\State
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\State")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_state", referencedColumnName="id")
     * })
     */
    private $fkState;



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
     * @return MerchantBranch
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
     * @return MerchantBranch
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
     * @return MerchantBranch
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
     * Set longitude
     *
     * @param float $longitude
     *
     * @return MerchantBranch
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return MerchantBranch
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return MerchantBranch
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
     * Set address
     *
     * @param string $address
     *
     * @return MerchantBranch
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     *
     * @return MerchantBranch
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set permalinkPath
     *
     * @param string $permalinkPath
     *
     * @return MerchantBranch
     */
    public function setPermalinkPath($permalinkPath)
    {
        $this->permalinkPath = $permalinkPath;

        return $this;
    }

    /**
     * Get permalinkPath
     *
     * @return string
     */
    public function getPermalinkPath()
    {
        return $this->permalinkPath;
    }

    /**
     * Set timeOffset
     *
     * @param string $timeOffset
     *
     * @return MerchantBranch
     */
    public function setTimeOffset($timeOffset)
    {
        $this->timeOffset = $timeOffset;

        return $this;
    }

    /**
     * Get timeOffset
     *
     * @return string
     */
    public function getTimeOffset()
    {
        return $this->timeOffset;
    }

    /**
     * Set apiKey
     *
     * @param string $apiKey
     *
     * @return MerchantBranch
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set eMail
     *
     * @param string $eMail
     *
     * @return MerchantBranch
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
     * Set fkCity
     *
     * @param \Application\Entity\City $fkCity
     *
     * @return MerchantBranch
     */
    public function setFkCity(\Application\Entity\City $fkCity = null)
    {
        $this->fkCity = $fkCity;

        return $this;
    }

    /**
     * Get fkCity
     *
     * @return \Application\Entity\City
     */
    public function getFkCity()
    {
        return $this->fkCity;
    }

    /**
     * Set fkCountry
     *
     * @param \Application\Entity\Country $fkCountry
     *
     * @return MerchantBranch
     */
    public function setFkCountry(\Application\Entity\Country $fkCountry = null)
    {
        $this->fkCountry = $fkCountry;

        return $this;
    }

    /**
     * Get fkCountry
     *
     * @return \Application\Entity\Country
     */
    public function getFkCountry()
    {
        return $this->fkCountry;
    }

    /**
     * Set fkMerchant
     *
     * @param \Application\Entity\Merchant $fkMerchant
     *
     * @return MerchantBranch
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
     * Set fkState
     *
     * @param \Application\Entity\State $fkState
     *
     * @return MerchantBranch
     */
    public function setFkState(\Application\Entity\State $fkState = null)
    {
        $this->fkState = $fkState;

        return $this;
    }

    /**
     * Get fkState
     *
     * @return \Application\Entity\State
     */
    public function getFkState()
    {
        return $this->fkState;
    }
}
