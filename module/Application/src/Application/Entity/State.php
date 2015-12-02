<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * State
 *
 * @ORM\Table(name="state", indexes={@ORM\Index(name="fk_country", columns={"fk_country"})})
 * @ORM\MappedSuperclass
 */
class State
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
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
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
     * @return State
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
     * @return State
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
}
