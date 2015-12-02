<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;

/**
 * Merchant
 *
 * @ORM\Table(name="merchant", indexes={@ORM\Index(name="fk_category_1", columns={"fk_category"})})
 * @ORM\Entity(repositoryClass="Application\Model\Repository\Merchant")
 */
class Merchant extends Entity\Merchant
{

}
