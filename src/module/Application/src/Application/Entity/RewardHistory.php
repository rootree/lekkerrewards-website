<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RewardHistory
 *
 * @ORM\Table(name="reward_history", indexes={@ORM\Index(name="fk_reward_43", columns={"fk_reward"})})
 * @ORM\MappedSuperclass
 */
class RewardHistory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="integer", nullable=false)
     */
    private $points;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=250, nullable=false)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=32, nullable=false)
     */
    private $code = '';

    /**
     * @var \Application\Entity\Reward
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Reward")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_reward", referencedColumnName="id")
     * })
     */
    private $fkReward;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return RewardHistory
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return RewardHistory
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return RewardHistory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return RewardHistory
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set fkReward
     *
     * @param \Application\Entity\Reward $fkReward
     *
     * @return RewardHistory
     */
    public function setFkReward(\Application\Entity\Reward $fkReward = null)
    {
        $this->fkReward = $fkReward;

        return $this;
    }

    /**
     * Get fkReward
     *
     * @return \Application\Entity\Reward
     */
    public function getFkReward()
    {
        return $this->fkReward;
    }
}
