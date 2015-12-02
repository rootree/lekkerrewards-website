<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;

/**
 * Redeem
 *
 * @ORM\Table(name="redeem", indexes={@ORM\Index(name="fk_merchant_7", columns={"fk_merchant"}), @ORM\Index(name="fk_merchant_branch_3", columns={"fk_merchant_branch"}), @ORM\Index(name="fk_customer_4", columns={"fk_customer"}), @ORM\Index(name="fk_reward_4", columns={"fk_reward"})})
 * @ORM\Entity(repositoryClass="Application\Model\Repository\Redeem")
 */
class Redeem extends Entity\Redeem
{
    const STATUS_ACTIVATED = 1;
}
