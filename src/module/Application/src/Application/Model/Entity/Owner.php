<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;

/**
 * Owner
 *
 * @ORM\Table(name="owner", indexes={@ORM\Index(name="fk_merchant_2", columns={"fk_merchant"})})
 * @ORM\Entity(repositoryClass="Application\Model\Repository\Owner")
 */
class Owner extends Entity\Owner
{

}
