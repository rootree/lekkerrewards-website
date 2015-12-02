<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;

/**
 * MerchantsCustomers
 *
 * @ORM\Table(name="merchants__customers", indexes={@ORM\Index(name="fk_merchant_8", columns={"fk_merchant"}), @ORM\Index(name="fk_customer_3", columns={"fk_customer"}), @ORM\Index(name="fk_merchant_branch_8", columns={"fk_merchant_branch"})})
 * @ORM\Entity(repositoryClass="Application\Model\Repository\MerchantsCustomers")
 */
class MerchantsCustomers extends Entity\MerchantsCustomers
{

}
