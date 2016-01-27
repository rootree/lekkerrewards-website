<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Merchant
 *
 * @ORM\Table(name="merchant", indexes={@ORM\Index(name="fk_category_1", columns={"fk_category"})})
 * @ORM\MappedSuperclass
 */
class Merchant
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=50, nullable=true)
     */
    private $facebook;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=50, nullable=true)
     */
    private $twitter;

    /**
     * @var string
     *
     * @ORM\Column(name="yelp_id", type="string", length=50, nullable=true)
     */
    private $yelpId;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=50, nullable=true)
     */
    private $website;

    /**
     * @var \Application\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_category", referencedColumnName="id")
     * })
     */
    private $fkCategory;



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
     * @return Merchant
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
     * @return Merchant
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
     * @return Merchant
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
     * Set name
     *
     * @param string $name
     *
     * @return Merchant
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
     * Set facebook
     *
     * @param string $facebook
     *
     * @return Merchant
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     *
     * @return Merchant
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set yelpId
     *
     * @param string $yelpId
     *
     * @return Merchant
     */
    public function setYelpId($yelpId)
    {
        $this->yelpId = $yelpId;

        return $this;
    }

    /**
     * Get yelpId
     *
     * @return string
     */
    public function getYelpId()
    {
        return $this->yelpId;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return Merchant
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set fkCategory
     *
     * @param \Application\Entity\Category $fkCategory
     *
     * @return Merchant
     */
    public function setFkCategory(\Application\Entity\Category $fkCategory = null)
    {
        $this->fkCategory = $fkCategory;

        return $this;
    }

    /**
     * Get fkCategory
     *
     * @return \Application\Entity\Category
     */
    public function getFkCategory()
    {
        return $this->fkCategory;
    }
}
