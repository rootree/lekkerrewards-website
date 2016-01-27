<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;

/**
 * Visit
 *
 * @ORM\Table(name="visit", indexes={@ORM\Index(name="fk_merchant_3", columns={"fk_merchant"}), @ORM\Index(name="fk_customer_1", columns={"fk_customer"}), @ORM\Index(name="fk_merchant_branch", columns={"fk_merchant_branch"})})
 * @ORM\Entity(repositoryClass="Application\Model\Repository\Visit")
 */
class Visit extends Entity\Visit
{
    const STATUS_OK = 1;
}
