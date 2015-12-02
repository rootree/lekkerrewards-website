<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;

/**
 * RewardHistory
 *
 * @ORM\Table(name="reward_history", indexes={@ORM\Index(name="fk_reward_43", columns={"fk_reward"})})
 * @ORM\Entity
 */
class RewardHistory extends Entity\RewardHistory
{

}
