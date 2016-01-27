<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * City
 *
 * @ORM\Table(name="city", indexes={@ORM\Index(name="fk_country_", columns={"fk_country"}), @ORM\Index(name="fk_state_", columns={"fk_state"})})
 * @ORM\MappedSuperclass
 */
class City
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
     * @var \Application\Entity\Country
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_country", referencedColumnName="id")
     * })
     */
    private $fkCountry;

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
     * Set name
     *
     * @param string $name
     *
     * @return City
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
     * Set fkCountry
     *
     * @param \Application\Entity\Country $fkCountry
     *
     * @return City
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
     * Set fkState
     *
     * @param \Application\Entity\State $fkState
     *
     * @return City
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
