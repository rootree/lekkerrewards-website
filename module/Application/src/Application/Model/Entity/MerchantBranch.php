<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * MerchantBranch
 *
 * @ORM\Table(name="merchant_branch", indexes={@ORM\Index(name="fk_merchant_1", columns={"fk_merchant"}), @ORM\Index(name="fk_city_1", columns={"fk_city"}), @ORM\Index(name="fk_country_1", columns={"fk_country"}), @ORM\Index(name="fk_state", columns={"fk_state"})})
 * @ORM\Entity(repositoryClass="Application\Model\Repository\MerchantBranch")
 */
class MerchantBranch extends Entity\MerchantBranch
{

    public function getFullAddress(){
        return sprintf('%s, %s %s', $this->getAddress(), $this->getZipcode(), $this->getFkCity()->getName());
    }

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Model\Entity\Reward", mappedBy="fkMerchant")
     * @ORM\JoinColumn(name="fk_merchant", referencedColumnName="id")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $rewardsList;

    public function __construct()
    {
        $this->rewardsList = new ArrayCollection();
    }

    /**
     * Get QR List
     *
     * @return \Application\Model\Entity\Reward[]
     */
    public function getRewardsList()
    {
        return $this->rewardsList;
    }
}
