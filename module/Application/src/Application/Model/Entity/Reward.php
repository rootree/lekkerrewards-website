<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Reward
 *
 * @ORM\Table(name="reward", indexes={@ORM\Index(name="fk_merchant_4", columns={"fk_merchant"}), @ORM\Index(name="fk_owner_1", columns={"fk_owner"}), @ORM\Index(name="fk_merchant_branch_2", columns={"fk_merchant_branch"})})
 * @ORM\Entity(repositoryClass="Application\Model\Repository\Reward")
 */
class Reward extends Entity\Reward
{
    const STATUS_ACTIVE = 1;
    const STATUS_ON_CHECK = 2;
    const STATUS_NO_ACTIVE = 0;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Model\Entity\RewardHistory", mappedBy="fkReward")
     * @ORM\JoinColumn(name="fk_reward", referencedColumnName="id")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $changesHistory;

    public function __construct()
    {
        $this->changesHistory = new ArrayCollection();
    }

    /**
     * @return \Application\Model\Entity\RewardHistory[]
     */
    public function getChangesHistory()
    {
        return $this->changesHistory;
    }
}
