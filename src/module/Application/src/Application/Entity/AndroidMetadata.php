<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AndroidMetadata
 *
 * @ORM\Table(name="android_metadata")
 * @ORM\MappedSuperclass
 */
class AndroidMetadata
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_please", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPlease;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="text", length=65535, nullable=true)
     */
    private $locale;



    /**
     * Get idPlease
     *
     * @return integer
     */
    public function getIdPlease()
    {
        return $this->idPlease;
    }

    /**
     * Set locale
     *
     * @param string $locale
     *
     * @return AndroidMetadata
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
