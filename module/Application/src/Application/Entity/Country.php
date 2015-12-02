<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="country")
 * @ORM\MappedSuperclass
 */
class Country
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="iso", type="string", length=2, nullable=false)
     */
    private $iso = '';

    /**
     * @var string
     *
     * @ORM\Column(name="nicename", type="string", length=80, nullable=false)
     */
    private $nicename = '';

    /**
     * @var string
     *
     * @ORM\Column(name="iso3", type="string", length=3, nullable=false)
     */
    private $iso3 = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="numcode", type="smallint", nullable=false)
     */
    private $numcode;

    /**
     * @var integer
     *
     * @ORM\Column(name="phonecode", type="smallint", nullable=false)
     */
    private $phonecode;



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
     * Set name
     *
     * @param string $name
     *
     * @return Country
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
     * Set iso
     *
     * @param string $iso
     *
     * @return Country
     */
    public function setIso($iso)
    {
        $this->iso = $iso;

        return $this;
    }

    /**
     * Get iso
     *
     * @return string
     */
    public function getIso()
    {
        return $this->iso;
    }

    /**
     * Set nicename
     *
     * @param string $nicename
     *
     * @return Country
     */
    public function setNicename($nicename)
    {
        $this->nicename = $nicename;

        return $this;
    }

    /**
     * Get nicename
     *
     * @return string
     */
    public function getNicename()
    {
        return $this->nicename;
    }

    /**
     * Set iso3
     *
     * @param string $iso3
     *
     * @return Country
     */
    public function setIso3($iso3)
    {
        $this->iso3 = $iso3;

        return $this;
    }

    /**
     * Get iso3
     *
     * @return string
     */
    public function getIso3()
    {
        return $this->iso3;
    }

    /**
     * Set numcode
     *
     * @param integer $numcode
     *
     * @return Country
     */
    public function setNumcode($numcode)
    {
        $this->numcode = $numcode;

        return $this;
    }

    /**
     * Get numcode
     *
     * @return integer
     */
    public function getNumcode()
    {
        return $this->numcode;
    }

    /**
     * Set phonecode
     *
     * @param integer $phonecode
     *
     * @return Country
     */
    public function setPhonecode($phonecode)
    {
        $this->phonecode = $phonecode;

        return $this;
    }

    /**
     * Get phonecode
     *
     * @return integer
     */
    public function getPhonecode()
    {
        return $this->phonecode;
    }
}
