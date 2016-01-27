<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;

/**
 * State
 *
 * @ORM\Table(name="state", indexes={@ORM\Index(name="fk_country", columns={"fk_country"})})
 * @ORM\Entity(repositoryClass="Application\Model\Repository\State")
 */
class State extends Entity\State
{

}
