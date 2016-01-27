<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;

/**
 * City
 *
 * @ORM\Table(name="city", indexes={@ORM\Index(name="fk_country_", columns={"fk_country"}), @ORM\Index(name="fk_state_", columns={"fk_state"})})
 * @ORM\Entity(repositoryClass="Application\Model\Repository\City")
 */
class City extends Entity\City
{

}
